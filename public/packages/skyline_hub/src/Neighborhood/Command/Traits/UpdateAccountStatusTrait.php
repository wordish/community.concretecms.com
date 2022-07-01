<?php

namespace PortlandLabs\Skyline\Neighborhood\Command\Traits;

use Concrete\Core\Express\ObjectManager;

trait UpdateAccountStatusTrait
{

    public function setStatus(string $neighborhood, string $siteHandle, int $status)
    {
        $objectManager = app(ObjectManager::class);
        $list = $objectManager->getList('skyline_hosting_site', true);
        $list->filterByAttribute('hosting_site_neighborhood', $neighborhood);
        $list->filterByAttribute('hosting_site_handle', $siteHandle);
        $sites = $list->getResults();
        foreach ($sites as $site) {
            $site->setAttribute('hosting_site_status', $status);
        }
    }


}
