<?php

namespace PortlandLabs\Skyline\Stripe\Webhook;

use Concrete\Core\Application\Application;
use Concrete\Core\Logging\LoggerAwareInterface;
use Concrete\Core\Logging\LoggerAwareTrait;
use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Command\ReinstateHostingSiteCommand;
use PortlandLabs\Skyline\Command\SuspendHostingSiteCommand;
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

    public function updateSubscriptionStatus(string $subscriptionId, string $newStatus)
    {
        $sites = $this->entityManager->getRepository(Site::class)->findBySubscriptionId($subscriptionId);
        foreach ($sites as $site) {
            $existingStatus = $site->getSubscriptionStatus();
            $this->logger->debug(t('Webhook :: Updating site %s (subscription %s), existing status %s - new status set to %s', $site->getId(), $subscriptionId, $existingStatus, $newStatus));
            $site->setSubscriptionStatus($newStatus);
            $this->entityManager->persist($site);
            if ($newStatus === 'unpaid') {
                // It's been X days past due (stripe settings control this) and so it's been marked as unpaid. Let's suspend
                // the site
                $this->app->executeCommand(new SuspendUnpaidHostingSiteCommand($site->getId()));
            }
            if ($newStatus === 'active' && $existingStatus === 'unpaid') {
                // The site has been brought back by paying outstanding invoices. Let's reinstate it.
                $this->app->executeCommand(new ReinstateHostingSiteCommand($site->getId()));
            }
        }
        $this->entityManager->flush();
    }

}
