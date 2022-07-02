<?php

namespace PortlandLabs\Skyline\Site;

use Concrete\Core\Entity\Express\Entry;
use Concrete\Core\Express\EntryList;
use Concrete\Core\Express\ObjectManager;

class SiteList extends EntryList
{

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var SiteFactory
     */
    protected $siteFactory;

    public function __construct(ObjectManager $objectManager, SiteFactory $siteFactory)
    {
        $this->objectManager = $objectManager;
        $this->siteFactory = $siteFactory;
        parent::__construct($this->objectManager->getObjectByHandle('skyline_hosting_site'));
    }

    public function getResult($queryRow): ?Site
    {
        $result = parent::getResult($queryRow);
        if ($result instanceof Entry) {
            return $this->siteFactory->createFromEntry($result);
        }
        return null;
    }


}
