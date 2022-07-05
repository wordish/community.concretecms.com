<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\User\User;
use Doctrine\ORM\EntityManager;
use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Neighborhood\Command\CreateSiteInSkylineCommand;
use PortlandLabs\Skyline\NeighborhoodSelector;
use PortlandLabs\Skyline\Site\SiteHandleGenerator;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateHostingSiteCommandHandler
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var MessageBusInterface
     */
    protected $messageBus;

    /**
     * @var NeighborhoodSelector
     */
    protected $neighborhoodSelector;

    /**
     * @var SiteHandleGenerator
     */
    protected $siteHandleGenerator;

    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user, EntityManager $entityManager, MessageBusInterface $messageBus, NeighborhoodSelector $neighborhoodSelector, SiteHandleGenerator $siteHandleGenerator)
    {
        $this->user = $user;
        $this->entityManager = $entityManager;
        $this->messageBus = $messageBus;
        $this->neighborhoodSelector = $neighborhoodSelector;
        $this->siteHandleGenerator = $siteHandleGenerator;
    }

    public function __invoke(CreateHostingSiteCommand $command)
    {
        $siteName = $command->getSiteName();
        $neighborhood = $this->neighborhoodSelector->chooseNeighborhoodForNewSite()->getHandle();
        $siteHandle = $this->siteHandleGenerator->createSiteHandle($command);
        $generatedAdminPassword = bin2hex(random_bytes(8));
        $author = $this->user->getUserInfoObject()->getEntityObject();

        $hostingEntry = new Site();
        $hostingEntry->setNeighborhood($neighborhood);
        $hostingEntry->setName($siteName);
        $hostingEntry->setHandle($siteHandle);
        $hostingEntry->setAuthor($author);
        $hostingEntry->setAdminPassword($generatedAdminPassword);
        $hostingEntry->setStatus(Site::STATUS_INSTALLING);
        $hostingEntry->setSubscriptionStatus($command->getSubscriptionStatus());
        $hostingEntry->setSubscriptionId($command->getSubscriptionId());

        $this->entityManager->persist($hostingEntry);
        $this->entityManager->flush();

        $command = new CreateSiteInSkylineCommand();
        $command->setNeighborhood($neighborhood);
        $command->setEmail($author->getUserEmail());
        $command->setSiteHandle($siteHandle);
        $command->setConcreteAdminPassword($generatedAdminPassword);

        $this->messageBus->dispatch($command);

        return $hostingEntry;
    }

    
}
