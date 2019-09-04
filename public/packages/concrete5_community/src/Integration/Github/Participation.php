<?php

namespace Concrete5\Community\Integration\Github;

use Concrete5\Community\Entity\GithubParticipation;
use Doctrine\ORM\EntityManagerInterface;

/**
 * A service for managing Partici
 */
class Participation
{

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Concrete5\Community\Entity\GithubParticipationRepository
     */
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Resolve the participation entity from login and repo
     *
     * @param string $login
     * @param string $repo
     *
     * @return \Concrete5\Community\Entity\GithubParticipation
     */
    public function getEntity(string $login, string $repo): ?GithubParticipation
    {
        $repository = $this->repository();
        $result = $repository->findOneBy([
            'login' => $login,
            'repository' => $repo,
        ]);

        return $result;
    }

    /**
     * Factory method for Participation objects
     *
     * @param string $login
     * @param string $repo
     *
     * @return \Concrete5\Community\Entity\GithubParticipation
     */
    public function createEntity(string $login, string $repo): GithubParticipation
    {
        $result = new GithubParticipation();
        $result->setLogin($login);
        $result->setRepository($repo);

        return $result;
    }

    /**
     * Lazy loaded / memoized repository fetcher
     *
     * @return \Concrete5\Community\Entity\GithubParticipationRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private function repository()
    {
        if (!$this->repository) {
            $this->repository = $this->entityManager->getRepository(GithubParticipation::class);
        }

        return $this->repository;
    }

}
