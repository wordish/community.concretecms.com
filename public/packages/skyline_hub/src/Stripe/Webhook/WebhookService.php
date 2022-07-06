<?php

namespace PortlandLabs\Skyline\Stripe\Webhook;

use Concrete\Core\Logging\LoggerAwareInterface;
use Concrete\Core\Logging\LoggerAwareTrait;
use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Logging\Channels;
use Stripe\Subscription;

class WebhookService implements LoggerAwareInterface
{

    use LoggerAwareTrait;

    public function getLoggerChannel()
    {
        return Channels::CHANNEL_SKYLINE_STRIPE;
    }

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * WebhookService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateSubscriptionStatus(string $subscriptionId, string $status)
    {
        $this->logger->debug(t('Updating subscription %s, status set to %s', $subscriptionId, $status));
        $db = $this->entityManager->getConnection();
        $db->update('SkylineSites', ['subscriptionStatus' => $status], ['subscriptionId' => $subscriptionId]);
    }

}
