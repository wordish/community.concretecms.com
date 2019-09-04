<?php

namespace Concrete5\Community\Lambda\Http\Controller;

use Concrete\Core\Config\Repository\Repository;
use Concrete5\Community\Integration\Github\Participation;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use function in_array;

class Github
{

    // Declare supported event types
    private const SUPPORTED_EVENT_TYPES = [
        'issues',
        'pull_request',
        'ping',
    ];

    /**
     * @var \Concrete\Core\Config\Repository\Repository
     */
    private $config;

    /**
     * @var \Concrete\Core\Database\Connection\Connection
     */
    private $connection;

    /**
     * @var \Concrete5\Community\Integration\Github\Participation
     */
    private $participation;

    public function __construct(Repository $config, EntityManagerInterface $connection, Participation $participation)
    {
        $this->config = $config;
        $this->connection = $connection;
        $this->participation = $participation;
    }

    /**
     * Lambda controller method for /github/webhook
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \GuzzleHttp\Psr7\Response
     */
    public function handleWebhook(ServerRequestInterface $request)
    {
        $signature = head($request->getHeader('X-Hub-Signature'));
        $id = head($request->getHeader('X-GitHub-Delivery'));
        $type = head($request->getHeader('X-GitHub-Event'));
        $method = Str::camel('handle_' . $type . '_event');

        // Make sure we support this type
        if (!in_array($type, self::SUPPORTED_EVENT_TYPES, true) || !method_exists($this, $method)) {
            // A 501 would be more appropriate, but I don't know how that would make lambda feel
            return new Response(200, [], '{"message": "Event type not supported."}');
        }

        // Validate Body
        $webhookSecret = $this->config->get('concrete5_community::integrations.github.webhook_secret');
        $body = $request->getBody()->getContents();
        $hash = hash_hmac('sha1', $body, $webhookSecret);

        // Github sends a sha1 hmac hash generated using our secret. We need to validate against that hash to be sure
        // this request is coming from github.
        if (!$body || !$hash || $hash !== $signature) {
            return new Response(403, [], '{"message": "Invalid request signature."}');
        }

        // ->handle{$Type}Event()
        return $this->{$method}($id, json_decode($body, true) ?? [], $request);
    }

    public function handleIssuesEvent(string $id, array $data, ServerRequestInterface $request)
    {
        $supportedActions = ['reopened', 'opened', 'closed', 'deleted'];
        $action = $data['action'] ?? null;

        if (!in_array($action, $supportedActions)) {
            return 'Unsupported Action';
        }

        $login = $data['issue']['user']['login'] ?? null;
        $repo = $data['repository']['full_name'] ?? null;

        if (!$login || !$repo) {
            return 'Unknown login / repository';
        }

        $participation = $this->participation->getEntity($login, $repo);

        // Handle newly added rows
        if (!$this->connection->contains($participation)) {
            $participation = $this->connection->transactional(function () use ($participation) {
                return $this->connection->merge($participation);
            });
        }

        $db = $this->connection->getConnection();
        $issuesOpenedDelta = 0;
        $closedIssuesDelta = 0;

        switch ($data['action']) {
            // The issue was reopened, we need to decrement the issue closed count
            case 'reopened':
                $closedIssuesDelta--;
                break;

            case 'opened':
                $issuesOpenedDelta++;
                break;

            case 'closed':
                $closedIssuesDelta++;
                break;

            case 'deleted':
                $issuesOpenedDelta--;
                if ($data['issue']['closed_at'] ?? null) {
                    $closedIssuesDelta--;
                }
                break;
        }

        // Do the actual work
        if ($closedIssuesDelta || $issuesOpenedDelta) {
            $result = $db->transactional(function (Connection $connection) use (
                $issuesOpenedDelta,
                $closedIssuesDelta,
                $login,
                $repo
            ) {
                $qb = $connection->createQueryBuilder();
                return $qb->update('GithubParticipation')
                    ->set('issuesOpened', 'issuesOpened + ' . $issuesOpenedDelta)
                    ->set('closedIssues', 'closedIssues + ' . $closedIssuesDelta)
                    ->where('login = :login')
                    ->andWhere('repository = :repo')
                    ->setParameters(['login' => $login, 'repo' => $repo])
                    ->execute();
            });

            return "Opened Issue: {$issuesOpenedDelta}, Closed Issue: {$closedIssuesDelta}";
        }

        return 'No change.';
    }

    public function handlePullRequestEvent(string $id, array $data, ServerRequestInterface $request)
    {
        $supportedActions = ['reopened', 'opened', 'closed', 'merged', 'deleted'];
        $action = $data['action'] ?? null;

        if (!in_array($action, $supportedActions)) {
            return 'Unsupported Action';
        }

        $login = $data['pull_request']['user']['login'] ?? null;
        $repo = $data['repository']['full_name'] ?? null;

        if (!$login || !$repo) {
            return 'Unknown login / repository';
        }

        $participation = $this->participation->getEntity($login, $repo);

        // Handle newly added rows
        if (!$participation) {
            $participation = $this->connection->transactional(function () use ($login, $repo) {
                $participation = $this->participation->createEntity($login, $repo);
                $this->connection->merge($participation);
            });
        }

        $db = $this->connection->getConnection();
        $pullsOpenedDelta = 0;
        $closedPullsDelta = 0;
        $mergedPullsDelta = 0;

        switch ($data['action']) {
            // The issue was reopened, we need to decrement the issue closed count
            case 'reopened':
                $closedPullsDelta--;
                break;

            case 'opened':
                $pullsOpenedDelta++;
                break;

            case 'closed':
                $closedPullsDelta++;
                break;

            case 'merged':
                $mergedPullsDelta++;
                break;

            case 'deleted':
                $pullsOpenedDelta--;
                if ($data['issue']['merged_at'] ?? null) {
                    $mergedPullsDelta--;
                } elseif ($data['issue']['closed_at'] ?? null) {
                    $closedPullsDelta--;
                }
                break;
        }

        // Do the actual work
        if ($pullsOpenedDelta || $closedPullsDelta || $mergedPullsDelta) {
            $result = $db->transactional(function (Connection $connection) use (
                $pullsOpenedDelta,
                $closedPullsDelta,
                $mergedPullsDelta,
                $login,
                $repo
            ) {
                $qb = $connection->createQueryBuilder();
                return $qb->update('GithubParticipation')
                    ->set('pullsOpened', 'pullsOpened + ' . $pullsOpenedDelta)
                    ->set('closedPulls', 'closedPulls + ' . $closedPullsDelta)
                    ->set('mergedPulls', 'mergedPulls + ' . $mergedPullsDelta)
                    ->where('login = :login')
                    ->andWhere('repository = :repo')
                    ->setParameters(['login' => $login, 'repo' => $repo])
                    ->execute();
            });

            return "Opened Pull: {$pullsOpenedDelta}, Closed Pull: {$closedPullsDelta}, Merged Pull: {$mergedPullsDelta}";
        }

        return 'No change.';
    }

    public function handlePingEvent(string $id, array $data, ServerRequestInterface $request)
    {
        return new Response(200, [], '{"message": "Pong: ' . $id . ' ' . ($data['hook_id'] ?? '') . '"}');
    }

}
