<?php

namespace PortlandLabs\Skyline\Neighborhood\Command;

use Concrete\Core\Express\ObjectManager;
use PortlandLabs\Skyline\Site\Site;

class CompleteAccountDeletionInNeighborhoodCommandHandler
{

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function __invoke(CompleteAccountDeletionInNeighborhoodCommand $command)
    {
        $list = $this->objectManager->getList('skyline_hosting_site', true);
        $list->filterByAttribute('hosting_site_neighborhood', $command->getNeighborhood());
        $list->filterByAttribute('hosting_site_handle', $command->getSiteHandle());
        $sites = $list->getResults();
        foreach ($sites as $site) {
            $site->setAttribute('hosting_site_status', Site::STATUS_TERMINATED);
        }
    }

    
}
