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
        $site->setDateAdded($entry->getDateCreated());
        $site->setHandle($entry->getAttribute('hosting_site_handle'));
        $site->setName($entry->getAttribute('hosting_site_name'));
        $site->setStatus($entry->getAttribute('hosting_site_status'));
        $site->setSubscriptionId((string) $entry->getAttribute('hosting_site_subscription_id'));
        $site->setSubscriptionStatus((string) $entry->getAttribute('hosting_site_subscription_status'));
        $site->setConcreteAdminPassword((string) $entry->getAttribute('hosting_site_password'));
        $neighborhood = $this->neighborhoodList->getByHandle($entry->getAttribute('hosting_site_neighborhood'));
        if ($neighborhood) {
            $site->setPublicUrl($neighborhood->getSitePublicUrl($site));
            $site->setPublicDomain($neighborhood->getSitePublicDomain($site));
        }
        $site->setControlPanelUrl((string) \URL::to('/account/hosting', 'project', $site->getId()));
        return $site;
    }

}
