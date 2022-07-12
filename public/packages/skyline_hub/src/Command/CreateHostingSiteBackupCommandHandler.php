<?php

namespace PortlandLabs\Skyline\Command;

use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Entity\Backup;
use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Neighborhood\Command\CreateAccountBackupInNeighborhoodCommand;
use PortlandLabs\Skyline\Neighborhood\Command\SuspendSiteInNeighborhoodCommand;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateHostingSiteBackupCommandHandler
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

    /**
     * @param CreateHostingSiteBackupCommand $command
     */
    public function __invoke($command)
    {
        $hostingEntry = $this->entityManager->find(Site::class, $command->getId());
        $backup = new Backup();
        $backup->setSite($hostingEntry);
        $this->entityManager->persist($backup);
        $this->entityManager->flush();

        $command = new CreateAccountBackupInNeighborhoodCommand();
        $command->setNeighborhood($hostingEntry->getNeighborhood());
        $command->setSiteHandle($hostingEntry->getHandle());
        $this->messageBus->dispatch($command);

        return $backup;
    }

    
}
