<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\Foundation\Command\Command;

class UpdateHostingSiteCommand extends Command
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $siteName;

    /**
     * @var int
     */
    protected $author;


    /**
     * @var string
     */
    protected $subscriptionId;


    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSiteName(): string
    {
        return $this->siteName;
    }

    /**
     * @param string $siteName
     */
    public function setSiteName(string $siteName): void
    {
        $this->siteName = $siteName;
    }

    /**
     * @return int
     */
    public function getAuthor(): int
    {
        return $this->author;
    }

    /**
     * @param int $author
     */
    public function setAuthor(int $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getSubscriptionId(): string
    {
        return $this->subscriptionId;
    }

    /**
     * @param string $subscriptionId
     */
    public function setSubscriptionId(string $subscriptionId): void
    {
        $this->subscriptionId = $subscriptionId;
    }




}
