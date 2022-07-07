<?php

namespace PortlandLabs\Skyline\Command;

use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Neighborhood\Command\SuspendUnpaidSiteInNeighborhoodCommand;
use Symfony\Component\Messenger\MessageBusInterface;

class SuspendUnpaidHostingSiteCommandHandler
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var MessageBusInterface
     */
    protected $messageBus;

    public function __construct(EntityManager $entityManager, MessageBusInterface $messageBus)
    {
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
    }


    public function __invoke(SuspendUnpaidHostingSiteCommand $command)
    {
        /**
         * @var $hostingEntry Site
         */
        $hostingEntry = $this->entityManager->find(Site::class, $command->getId());
        $hostingEntry->setStatus(Site::STATUS_SUSPENDED_UNPAID);
        $hostingEntry->setSuspendedTimestamp((new \DateTime())->getTimestamp());
        $this->entityManager->persist($hostingEntry);
        $this->entityManager->flush();

        $command = new SuspendUnpaidSiteInNeighborhoodCommand();
        $command->setNeighborhood($hostingEntry->getNeighborhood());
        $command->setSiteHandle($hostingEntry->getHandle());

        $this->messageBus->dispatch($command);

        return $hostingEntry;
    }

    
}
