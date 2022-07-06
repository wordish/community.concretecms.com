<?php

namespace PortlandLabs\Skyline\Command;

use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Neighborhood\Command\DeleteSiteInSkylineCommand;
use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Stripe\StripeService;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteHostingSiteCommandHandler
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


    public function __invoke(DeleteHostingSiteCommand $command)
    {
        /**
         * @var $hostingEntry Site
         */
        $hostingEntry = $this->entityManager->find(Site::class, $command->getId());
        $hostingEntry->setStatus(Site::STATUS_DELETED_REMOVAL_IMMINENT);
        $this->entityManager->persist($hostingEntry);
        $this->entityManager->flush();

        try {
            $subscriptionId = $hostingEntry->getSubscriptionId();
            $this->stripeService->cancelSubscription($subscriptionId);
        } catch (\Exception $e) {}

        $command = new DeleteSiteInSkylineCommand();
        $command->setNeighborhood($hostingEntry->getNeighborhood());
        $command->setSiteHandle($hostingEntry->getHandle());

        $this->messageBus->dispatch($command);

        return $hostingEntry;
    }

    
}
