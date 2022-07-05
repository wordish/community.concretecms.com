<?php

/**
 *
 * This file was build with the Entity Designer add-on.
 *
 * https://www.concrete5.org/marketplace/addons/entity-designer
 *
 */

/** @noinspection DuplicatedCode */

namespace PortlandLabs\Skyline\Search\ItemList\Pager\Manager;

use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Search\ColumnSet\Available;
use Concrete\Core\Search\ItemList\Pager\PagerProviderInterface;
use Concrete\Core\Search\ItemList\Pager\Manager\AbstractPagerManager;
use Doctrine\ORM\EntityManagerInterface;
use Concrete\Core\Support\Facade\Application;

class SiteListPagerManager extends AbstractPagerManager
{
    /** 
     * @param Site $hostingSite
     * @return int 
     */
    public function getCursorStartValue($hostingSite)
    {
        return $hostingSite->getId();
    }
    
    public function getCursorObject($cursor)
    {
        $app = Application::getFacadeApplication();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $app->make(EntityManagerInterface::class);
        return $entityManager->getRepository(Site::class)->findOneBy(["id" => $cursor]);
    }
    
    public function getAvailableColumnSet()
    {
        return new Available();
    }
    
    public function sortListByCursor(PagerProviderInterface $itemList, $direction)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $itemList->getQueryObject()->addOrderBy('t0.id', $direction);
    }
}
