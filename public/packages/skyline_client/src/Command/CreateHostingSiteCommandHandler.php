<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\Entity\Express\Entry;
use Concrete\Core\Express\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

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
     * CreateHostingSiteCommandHandler constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager, Request $request)
    {
        $this->objectManager = $objectManager;
        $this->request = $request;
    }

    public function __invoke(CreateHostingSiteCommand $command)
    {
        $entity = $this->objectManager->getObjectByHandle('skyline_hosting_site');
        $controller = $this->objectManager->getEntityController($entity);
        $entryManager = $controller->getEntryManager($this->request);
        /**
         * @var $hostingEntry Entry
         */
        $hostingEntry = $entryManager->addEntry($entity);
        $hostingEntry->setAttribute('hosting_site_subscription_id', $command->getSubscriptionId());
        $hostingEntry->setAttribute('hosting_site_name', $command->getSiteName());


        return $hostingEntry;
    }

    
}
