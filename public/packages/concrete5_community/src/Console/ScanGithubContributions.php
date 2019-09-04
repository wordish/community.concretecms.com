<?php

namespace Concrete5\Community\Console;

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Console\Command;
use Concrete5\Community\Entity\GithubParticipation;
use Concrete5\Community\Integration\Github\GraphqlClient;
use Concrete5\Community\Integration\Github\Participation;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use OAuth\Common\Exception\Exception;
use OAuth\OAuth2\Token\StdOAuth2Token;
use function is_array;
use const JSON_PRETTY_PRINT;

class ScanGithubContributions extends Command
{

    // Error Codes
    private const E_NONE = 0;
    private const E_TOKEN_NOT_SET = 2;
    private const E_QUERY_FAILED = 3;

    /**
     * Cache for entities waiting to be flushed
     *
     * @var array
     */
    protected $entityCache = [];

    protected $signature = 'integrations:github:scan';

    /**
     * Handle calls to this command
     *
     * @param \Concrete5\Community\Integration\Github\GraphqlClient $client
     * @param \Concrete\Core\Config\Repository\Repository $config
     * @param \Concrete5\Community\Integration\Github\Participation $participation
     * @param \Doctrine\ORM\EntityManagerInterface $orm
     *
     * @return int
     */
    public function handle(
        GraphqlClient $client,
        Repository $config,
        Participation $participation,
        EntityManagerInterface $orm
    ): int {
        $token = $config->get('concrete5_community::integrations.github.access_token');
        if (!$token) {
            $this->output->error('Config item "concrete5_community::integrations.github.access_token" isn\'t set!');
            return self::E_TOKEN_NOT_SET;
        }

        $service = $client->getService();
        $service->getStorage()->storeAccessToken($service->service(), new StdOAuth2Token($token));

        try {
            $client->debug(function ($query, $params) {
                $this->output->writeln('<info>Sending Query:</info>');
                $this->output->writeln('<info>' . $query . '</info>');
                $this->output->newLine();
                $this->output->writeln(json_encode($params, JSON_PRETTY_PRINT));
                $this->output->newLine();
            });

            $repositories = $client->query('fetch_repositories');
            foreach ($repositories['data']['organization']['repositories']['nodes'] ?? [] as $repository) {
                list($owner, $name) = explode('/', $repository['nameWithOwner'] ?? '/');
                $this->processRepository($owner, $name, $client, $participation, $orm);
            }
        } catch (Exception $e) {
            $this->output->error('Failed to query against github\'s graphql api: ' . $e->getMessage());
            return self::E_QUERY_FAILED;
        }

        $this->flush($orm);
        return self::E_NONE;
    }

    /**
     * Process a known repository
     * This method recieves information about a repository we'd like to scan and then scans relevant stuff like:
     *   - Issues opened and their resulting status
     *   - Pull requests opened and their resulting status
     *
     * @param string $owner
     * @param string $name
     * @param \Concrete5\Community\Integration\Github\GraphqlClient $client
     * @param \Concrete5\Community\Integration\Github\Participation $participation
     * @param \Doctrine\ORM\EntityManagerInterface $orm
     *
     * @throws \OAuth\Common\Exception\Exception
     * @throws \OAuth\Common\Token\Exception\ExpiredTokenException
     */
    public function processRepository(
        string $owner,
        string $name,
        GraphqlClient $client,
        Participation $participation,
        EntityManagerInterface $orm
    ): void {
        $progress = null;
        $start = 0;
        $types = ['pullRequests', 'issues'];

        foreach ($types as $type) {
            $pages = $client->paginate($type, 'cursor', 'data.repository.' . $type . '.pageInfo', [
                'owner' => $owner,
                'name' => $name,
            ]);

            // Loop over individual results
            foreach ($client->streamPagination($pages, 'data.repository.' . $type . '.nodes') as $item) {
                if ($item instanceof DateTime) {
                    if (!$progress) {
                        $start = $item->getTimestamp() - time();

                        $progress = $this->output->createProgressBar($start);
                        $progress->setFormat('<info>Throttled! Waiting %remaining% until next window.</info>');
                        $progress->display();
                    } else {
                        $progress->setProgress($start - ($item->getTimestamp() - time()));
                    }

                    continue;
                }

                // Handle the throttle expiring
                if ($progress) {
                    $progress->finish();
                    $progress = null;
                }

                // Handle individual results
                if (is_array($item)) {
                    $this->handleItem($item, $participation, $orm);
                }
            }
        }
    }

    /**
     * Handle an issue action item
     *
     * @param array $item
     * @param \Concrete5\Community\Integration\Github\Participation $participation
     * @param \Doctrine\ORM\EntityManagerInterface $orm
     */
    public function handleItem(array $item, Participation $participation, EntityManagerInterface $orm): void
    {
        $isPull = isset($item['merged']);
        $created = $item['createdAt'] ?? null;
        $login = $item['author']['login'] ?? null;
        $repo = $item['repository']['nameWithOwner'] ?? null;
        $state = $item['state'];

        if (!$login || !$repo || !$state) {
            return;
        }

        $entity = $this->persist(
            $this->getCache($login, $repo) ?? // First check cache
            $participation->getEntity($login, $repo) ?? // Second check database
            $participation->createEntity($login, $repo),
            $orm); // Last create a fresh one

        if ($isPull) {
            $this->handlePullItem($entity, $state);
        } else {
            $this->handleIssueItem($entity, $state);
        }
    }

    public function handlePullItem(GithubParticipation $participation, string $state)
    {
        switch (strtoupper($state)) {
            case 'MERGED':
                $participation->setMergedPulls($participation->getMergedPulls() + 1);
                break;
            case 'CLOSED':
                $participation->setClosedPulls($participation->getClosedPulls() + 1);
                break;
        }

        $participation->setPullsOpened($participation->getPullsOpened() + 1);
    }

    public function handleIssueItem(GithubParticipation $participation, string $state)
    {
        if (strtoupper($state) === 'CLOSED') {
            $participation->setClosedIssues($participation->getClosedIssues() + 1);
        }

        $participation->setIssuesOpened($participation->getIssuesOpened() + 1);
    }

    /**
     * Attempt to persist a given participation object
     *
     * @param \Concrete5\Community\Entity\GithubParticipation $participation
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     *
     * @return \Concrete5\Community\Entity\GithubParticipation
     */
    private function persist(
        GithubParticipation $participation,
        EntityManagerInterface $entityManager
    ): GithubParticipation {
        if (count($this->entityCache) >= 50) {
            $this->flush($entityManager);
        }

        $entity = $entityManager->merge($participation);
        $this->setCache($entity);

        return $entity;
    }

    /**
     * Get participation from cache
     *
     * @param string $login
     * @param string $repo
     *
     * @return \Concrete5\Community\Entity\GithubParticipation|null
     */
    private function getCache(string $login, string $repo): ?GithubParticipation
    {
        return $this->entityCache[strtolower($login . '::' . $repo)] ?? null;
    }

    /**
     * Store pending participation in cache
     *
     * @param \Concrete5\Community\Entity\GithubParticipation $participation
     */
    private function setCache(GithubParticipation $participation): void
    {
        $this->entityCache[strtolower($participation->getLogin() . '::' . $participation->getRepository())] = $participation;
    }

    /**
     * Flush the entity manager and clear cache
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    private function flush(EntityManagerInterface $entityManager): void
    {
        $this->output->note('Flushing entities...');
        $this->entityCache = [];
        $entityManager->flush();
    }
}
