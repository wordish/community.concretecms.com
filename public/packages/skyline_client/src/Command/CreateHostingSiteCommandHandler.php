<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\Entity\Express\Entry;
use Concrete\Core\Express\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;

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
     * CreateHostingSiteCommandHandler constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager, Request $request, MessageBusInterface $messageBus)
    {
        $this->objectManager = $objectManager;
        $this->request = $request;
        $this->messageBus = $messageBus;
    }

    public function __invoke(CreateHostingSiteCommand $command)
    {
        $entity = $this->objectManager->getObjectByHandle('skyline_hosting_site');
        $controller = $this->objectManager->getEntityController($entity);
        $entryManager = $controller->getEntryManager($this->request);
        $hostingEntry = $entryManager->addEntry($entity);
        $hostingEntry->setAttribute('hosting_site_subscription_id', $command->getSubscriptionId());
        $hostingEntry->setAttribute('hosting_site_name', $command->getSiteName());

        $command = new CreateSiteInSkylineCommand();
        $this->messageBus->dispatch($command, [new AmqpStamp('irvington')]);

        return $hostingEntry;
    }

    
}
