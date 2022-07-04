<?php

namespace PortlandLabs\Skyline\Command;

use Concrete\Core\Express\ObjectManager;
use PortlandLabs\Skyline\Neighborhood\Command\TerminateTrialSiteInSkylineCommand;
use PortlandLabs\Skyline\Site\Site;
use PortlandLabs\Skyline\Site\SiteFactory;
use PortlandLabs\Skyline\Stripe\StripeService;
use Symfony\Component\Messenger\MessageBusInterface;

class TerminateHostingTrialSiteCommandHandler
{

    /**
     * @var SiteFactory
     */
    protected $siteFactory;

    /**
     * @var StripeService
     */
    protected $stripeService;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var MessageBusInterface
     */
    protected $messageBus;

    public function __construct(SiteFactory $siteFactory, StripeService $stripeService, ObjectManager $objectManager, MessageBusInterface $messageBus)
    {
        $this->siteFactory = $siteFactory;
        $this->stripeService = $stripeService;
        $this->objectManager = $objectManager;
        $this->messageBus = $messageBus;
    }


    public function __invoke(TerminateHostingTrialSiteCommand $command)
    {

        $hostingEntry = $this->objectManager->getEntryByPublicIdentifier($command->getId());
        $hostingEntry->setAttribute('hosting_site_status', Site::STATUS_TRIAL_SUSPENDED);
        $hostingEntry->setAttribute('hosting_site_suspended_timestamp', (new \DateTime())->getTimestamp());

        $site = $this->siteFactory->createFromEntry($hostingEntry);

        $subscriptionId = $site->getSubscriptionId();
        $this->stripeService->cancelSubscription($subscriptionId);

        $command = new TerminateTrialSiteInSkylineCommand();
        $command->setNeighborhood($site->getNeighborhood());
        $command->setSiteHandle($site->getHandle());

        $this->messageBus->dispatch($command);

        return $hostingEntry;
    }

    
}
