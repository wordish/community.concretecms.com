<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Project;

use Concrete\Core\User\UserInfoRepository;

class Project
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $dateCreated;

    /**
     * @var int
     */
    protected $userId;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getDateCreated(): ?int
    {
        return $this->dateCreated;
    }

    /**
     * @param int $dateCreated
     */
    public function setDateCreated(int $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return int
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getDateCreatedString()
    {
        $created = new \DateTime();
        $created->setTimestamp($this->getDateCreated());
        return $created->format('M d, Y g:i a');
    }

    public function getUserDisplayName()
    {
        /**
         * @var $repository UserInfoRepository
         */
        $repository = app(UserInfoRepository::class);
        $user = null;
        if ($this->getUserId()) {
            $user = $repository->getByID($this->getUserId());
        }
        if ($user) {
            return $user->getUserDisplayName();
        } else {
            return t('(Not Available.');
        }
    }

    public function getSiteTypeString()
    {
        return t('Third Party');
    }

}
