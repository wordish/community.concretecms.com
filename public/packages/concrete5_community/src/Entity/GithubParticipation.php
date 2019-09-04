<?php

namespace Concrete5\Community\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class GithubParticipation
 *
 * @package Concrete5\Community\Entities
 * @ORM\Entity(repositoryClass="Concrete5\Community\Entity\GithubParticipationRepository")
 * @ORM\Table(name="GithubParticipation", indexes={
 *   @ORM\Index(name="locator", columns={"repository", "login"}),
 *   @ORM\Index(name="login_search", columns={"login"}),
 *   @ORM\Index(name="repository_search", columns={"repository"})
 * }, uniqueConstraints={
 *   @ORM\UniqueConstraint(name="unique_repo_login", columns={"repository", "login"}),
 * })
 */
class GithubParticipation
{

    /**
     * @var string
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Id()
     */
    protected $id;

    /**
     * Max length for org name is 60, max length for repo name is 100
     *
     * @var string
     * @ORM\Column(type="string", length=161)
     */
    protected $repository;

    /**
     * Max length for github username is 39 chars
     *
     * @var string
     * @ORM\Column(type="string", length=39)
     */
    protected $login;

    /**
     * Total opened pulls, this should equal (currently open pulls + closed pulls + merged pulls)
     *
     * @var int
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    protected $pullsOpened = 0;

    /**
     * Of the opened pulls, how many were closed without merging
     *
     * @var int
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    protected $closedPulls = 0;

    /**
     * Of the opened pulls, how many were merged
     *
     * @var int
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    protected $mergedPulls = 0;

    /**
     * The total number of opened issues, this should be more than or equal to the number of closed issues
     *
     * @var int
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    protected $issuesOpened = 0;

    /**
     * Of the opened issues, how many were closed
     *
     * @var int
     * @ORM\Column(type="integer", options={"unsigned":true})
     */
    protected $closedIssues = 0;

    /**
     * Get the ID of this participation
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return GithubParticipation
     */
    public function setId(string $id): GithubParticipation
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the associated repository
     * EX: bazorg/foo_bar
     *
     * @return string
     */
    public function getRepository(): string
    {
        return $this->repository;
    }

    /**
     * Set the repository this participation is associated with
     *
     * @param string $repository
     *
     * @return GithubParticipation
     */
    public function setRepository(string $repository): GithubParticipation
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * Get the github username
     *
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * Set the github username
     *
     * @param string $login
     *
     * @return GithubParticipation
     */
    public function setLogin(string $login): GithubParticipation
    {
        $this->login = $login;
        return $this;
    }

    /**
     * Get the total number of pulls opened
     *
     * @return int
     */
    public function getPullsOpened(): int
    {
        return $this->pullsOpened;
    }

    /**
     * Set the total number of opened pulls
     *
     * @param int $pullsOpened
     *
     * @return GithubParticipation
     */
    public function setPullsOpened(int $pullsOpened): GithubParticipation
    {
        $this->pullsOpened = $pullsOpened;
        return $this;
    }

    /**
     * Get the number of opened pulls that ended up merged
     *
     * @return int
     */
    public function getClosedPulls(): int
    {
        return $this->closedPulls;
    }

    /**
     * Set the number of opened pulls that ended up closed
     *
     * @param int $closedPulls
     *
     * @return GithubParticipation
     */
    public function setClosedPulls(int $closedPulls): GithubParticipation
    {
        $this->closedPulls = $closedPulls;
        return $this;
    }

    /**
     * Get the number of opened pulls that ended up merged
     *
     * @return int
     */
    public function getMergedPulls(): int
    {
        return $this->mergedPulls;
    }

    /**
     * Set the total number of opened pulls that ended up merged
     *
     * @param int $mergedPulls
     *
     * @return GithubParticipation
     */
    public function setMergedPulls(int $mergedPulls): GithubParticipation
    {
        $this->mergedPulls = $mergedPulls;
        return $this;
    }

    /**
     * Determine the number of pulls that must be open
     *
     * @return int
     */
    public function getOpenPulls(): int
    {
        return $this->getPullsOpened() - $this->getMergedPulls() - $this->getClosedPulls();
    }

    /**
     * Get the total number of issues that were opened
     *
     * @return int
     */
    public function getIssuesOpened(): int
    {
        return $this->issuesOpened;
    }

    /**
     * Set the total number of opened issues
     *
     * @param int $issuesOpened
     *
     * @return GithubParticipation
     */
    public function setIssuesOpened(int $issuesOpened): GithubParticipation
    {
        $this->issuesOpened = $issuesOpened;
        return $this;
    }

    /**
     * Get the number of opened issues that ended up closed
     *
     * @return int
     */
    public function getClosedIssues(): int
    {
        return $this->closedIssues;
    }

    /**
     * Determine the number of issues that must be open
     *
     * @return int
     */
    public function getOpenIssues(): int
    {
        return $this->getIssuesOpened() - $this->getClosedIssues();
    }

    /**
     * Set the number of opened issues that ended up closed
     *
     * @param int $closedIssues
     *
     * @return GithubParticipation
     */
    public function setClosedIssues(int $closedIssues): GithubParticipation
    {
        $this->closedIssues = $closedIssues;
        return $this;
    }

}
