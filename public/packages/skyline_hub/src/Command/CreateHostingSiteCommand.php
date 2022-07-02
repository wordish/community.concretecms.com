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
     * Derived from the stripe object. We'll almost always be 'trialing' when the site is created but who knows
     * what the future holds
     *
     * @var string
     */
    protected $subscriptionStatus;

    /**
     * @var string
     */
    protected $siteName;


    public function __construct(string $subscriptionId, string $subscriptionStatus, string $siteName)
    {
        $this->subscriptionId = $subscriptionId;
        $this->subscriptionStatus = $subscriptionStatus;
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
     * @return string
     */
    public function getSubscriptionStatus(): string
    {
        return $this->subscriptionStatus;
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
