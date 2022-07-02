<?php

namespace PortlandLabs\Skyline\Neighborhood\Command\Traits;

use Concrete\Core\Express\ObjectManager;
use Doctrine\ORM\EntityManager;

trait UpdateAccountTrait
{

    public function setStatus(string $neighborhood, string $siteHandle, array $attributes)
    {
        // These next two lines are required due to the way this runs inside the always-running ConsumeMessagesCommand
        $entityManager = app(EntityManager::class);
        $entityManager->clear();

        $objectManager = app(ObjectManager::class);
        $list = $objectManager->getList('skyline_hosting_site', true);
        $list->filterByAttribute('hosting_site_neighborhood', $neighborhood);
        $list->filterByAttribute('hosting_site_handle', $siteHandle);
        $sites = $list->getResults();
        foreach ($sites as $site) {
            foreach ($attributes as $key => $value) {
                if ($value === null) {
                    $site->clearAttribute($key);
                } else {
                    $site->setAttribute($key, $value);
                }
            }
        }
    }


}
