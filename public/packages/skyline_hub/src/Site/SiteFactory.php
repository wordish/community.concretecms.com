<?php

namespace PortlandLabs\Skyline\Site;

use Concrete\Core\Entity\Express\Entry;
use PortlandLabs\Skyline\NeighborhoodListFactory;
use PortlandLabs\Skyline\NeighborhoodList;

class SiteFactory
{

    /**
     * @var NeighborhoodList
     */
    protected $neighborhoodList;

    public function __construct(NeighborhoodListFactory $neighborhoodListFactory)
    {
        $this->neighborhoodList = $neighborhoodListFactory->createList();
    }

    public function createFromEntry(Entry $entry): Site
    {
        $site = new Site();
        $site->setId($entry->getPublicIdentifier());
        $site->setHandle($entry->getAttribute('hosting_site_handle'));
        $site->setStatus($entry->getAttribute('hosting_site_status'));
        $site->setConcreteAdminPassword($entry->getAttribute('hosting_site_password'));
        $neighborhood = $this->neighborhoodList->getByHandle($entry->getAttribute('hosting_site_neighborhood'));
        if ($neighborhood) {
            $site->setPublicUrl($neighborhood->getSitePublicUrl($site));
            $site->setPublicDomain($neighborhood->getSitePublicDomain($site));
        }
        return $site;
    }

}
