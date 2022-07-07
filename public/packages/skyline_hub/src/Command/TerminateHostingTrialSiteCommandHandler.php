<?php

namespace PortlandLabs\Skyline\Command;

use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Neighborhood\Command\SuspendTrialSiteInNeighborhoodCommand;
use PortlandLabs\Skyline\Stripe\StripeService;
use Symfony\Component\Messenger\MessageBusInterface;

class TerminateHostingTrialSiteCommandHandler
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


    public function __invoke(TerminateHostingTrialSiteCommand $command)
    {
        /**
         * @var $hostingEntry Site
         */
        $hostingEntry = $this->entityManager->find(Site::class, $command->getId());
        $hostingEntry->setStatus(Site::STATUS_SUSPENDED_TRIAL_CANCELLED);
        $hostingEntry->setSuspendedTimestamp((new \DateTime())->getTimestamp());
        $this->entityManager->persist($hostingEntry);
        $this->entityManager->flush();

        $subscriptionId = $hostingEntry->getSubscriptionId();
        $this->stripeService->cancelSubscription($subscriptionId);

        $command = new SuspendTrialSiteInNeighborhoodCommand();
        $command->setNeighborhood($hostingEntry->getNeighborhood());
        $command->setSiteHandle($hostingEntry->getHandle());

        $this->messageBus->dispatch($command);

        return $hostingEntry;
    }

    
}
