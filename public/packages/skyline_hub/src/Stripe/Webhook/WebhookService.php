<?php

namespace PortlandLabs\Skyline\Stripe\Webhook;

use Concrete\Core\Application\Application;
use Concrete\Core\Logging\LoggerAwareInterface;
use Concrete\Core\Logging\LoggerAwareTrait;
use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Command\SuspendUnpaidHostingSiteCommand;
use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Logging\Channels;

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
     * @var Application
     */
    protected $app;

    /**
     * WebhookService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(Application $app, EntityManager $entityManager)
    {
        $this->app = $app;
        $this->entityManager = $entityManager;
    }

    public function updateSubscriptionStatus(string $subscriptionId, string $status)
    {
        $this->logger->debug(t('Updating subscription %s, status set to %s', $subscriptionId, $status));
        $db = $this->entityManager->getConnection();
        $db->update('SkylineSites', ['subscriptionStatus' => $status], ['subscriptionId' => $subscriptionId]);

        if ($status === 'unpaid') {
            // It's been X days past due (stripe settings control this) and so it's been marked as unpaid. Let's suspend
            // the site
            $sites = $this->entityManager->getRepository(Site::class)->findBySubscriptionId($subscriptionId);
            foreach ($sites as $site) {
                $command = new SuspendUnpaidHostingSiteCommand($site->getId());
                $this->app->executeCommand($command);
            }
        }
    }

}
