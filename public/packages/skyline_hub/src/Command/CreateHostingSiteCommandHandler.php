<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\Entity\Express\Entry;
use Concrete\Core\Express\ObjectManager;
use PortlandLabs\Skyline\NeighborhoodSelector;
use PortlandLabs\Skyline\Site\SiteHandleGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use PortlandLabs\Skyline\Neighborhood\Command\CreateSiteInSkylineCommand;

class CreateHostingSiteCommandHandler
{

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var Request
     */
    protected $request;

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
     * CreateHostingSiteCommandHandler constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager, Request $request, MessageBusInterface $messageBus, NeighborhoodSelector $neighborhoodSelector, SiteHandleGenerator $siteHandleGenerator)
    {
        $this->objectManager = $objectManager;
        $this->request = $request;
        $this->messageBus = $messageBus;
        $this->neighborhoodSelector = $neighborhoodSelector;
        $this->siteHandleGenerator = $siteHandleGenerator;
    }

    public function __invoke(CreateHostingSiteCommand $command)
    {
        $siteName = $command->getSiteName();
        $neighborhood = $this->neighborhoodSelector->chooseNeighborhoodForNewSite();
        $siteHandle = $this->siteHandleGenerator->createSiteHandle($command);
        $entity = $this->objectManager->getObjectByHandle('skyline_hosting_site');
        $controller = $this->objectManager->getEntityController($entity);
        $entryManager = $controller->getEntryManager($this->request);
        $hostingEntry = $entryManager->addEntry($entity);
        $hostingEntry->setAttribute('hosting_site_subscription_id', $command->getSubscriptionId());
        $hostingEntry->setAttribute('hosting_site_name', $siteName);
        $hostingEntry->setAttribute('hosting_site_handle', $siteHandle);
        $hostingEntry->setAttribute('hosting_site_neighborhood', $neighborhood);

        $command = new CreateSiteInSkylineCommand();
        $command->setNeighborhood($neighborhood);
        $command->setSiteHandle($siteHandle);

        $this->messageBus->dispatch($command);

        return $hostingEntry;
    }

    
}
