<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\Foundation\Command\Command;

class CreateHostingSiteCommand extends Command
{

    /**
     * @var string
     */
    protected $subscriptionId;

    /**
     * @var string
     */
    protected $siteName;


    public function __construct(string $subscriptionId, string $siteName)
    {
        $this->subscriptionId = $subscriptionId;
        $this->siteName = $siteName;
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


}
