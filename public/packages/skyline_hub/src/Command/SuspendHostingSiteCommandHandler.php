<?php

namespace PortlandLabs\Skyline\Command;

use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Neighborhood\Command\SuspendSiteInNeighborhoodCommand;
use PortlandLabs\Skyline\Stripe\StripeService;
use Symfony\Component\Messenger\MessageBusInterface;

class SuspendHostingSiteCommandHandler
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var StripeService
     */
    protected $stripeService;

    /**
     * @var MessageBusInterface
     */
    protected $messageBus;

    public function __construct(EntityManager $entityManager, StripeService $stripeService, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->stripeService = $stripeService;
        $this->messageBus = $messageBus;
    }

    protected function getEntryFromCommand(SuspendHostingSiteCommand $command): Site
    {
        /**
         * @var $hostingEntry Site
         */
        $hostingEntry = $this->entityManager->find(Site::class, $command->getId());
        return $hostingEntry;
    }

    protected function cancelHostingSubscriptionIfExists(Site $hostingEntry)
    {
        try {
            $subscriptionId = $hostingEntry->getSubscriptionId();
            $this->stripeService->cancelSubscription($subscriptionId);
        } catch (\Exception $e) {}
    }

    protected function setSuspendedStatus(Site $hostingEntry, int $status)
    {
        $hostingEntry->setStatus($status);
        $hostingEntry->setSuspendedTimestamp((new \DateTime())->getTimestamp());
        $this->entityManager->persist($hostingEntry);
        $this->entityManager->flush();
    }

    protected function dispatchNeighborhoodCommand($command, Site $hostingEntry)
    {
        $command->setNeighborhood($hostingEntry->getNeighborhood());
        $command->setSiteHandle($hostingEntry->getHandle());
        $this->messageBus->dispatch($command);
    }

    /**
     * @param SuspendHostingSiteCommand $command
     */
    public function __invoke($command)
    {
        $hostingEntry = $this->getEntryFromCommand($command);
        $this->setSuspendedStatus($hostingEntry, Site::STATUS_SUSPENDED_ADMIN_SUSPENDED);
        $this->dispatchNeighborhoodCommand(new SuspendSiteInNeighborhoodCommand(), $hostingEntry);
        return $hostingEntry;
    }

    
}
