<?php

/**
 *
 * This file was build with the Entity Designer add-on.
 *
 * https://www.concrete5.org/marketplace/addons/entity-designer
 *
 */

/** @noinspection DuplicatedCode */

namespace PortlandLabs\Skyline\Search\Site\ColumnSet\Column;

use Concrete\Core\Search\Column\Column;
use Concrete\Core\Search\Column\PagerColumnInterface;
use Concrete\Core\Search\ItemList\Pager\PagerProviderInterface;
use PortlandLabs\Skyline\Entity\Site;
use PortlandLabs\Skyline\Site\SiteList;

class StatusColumn extends Column implements PagerColumnInterface
{
    public function getColumnKey()
    {
        return 't0.status';
    }
    
    public function getColumnName()
    {
        return t('Status');
    }
    
    public function getColumnCallback()
    {
        return 'getStatus';
    }
    
    /**
    * @param SiteList $itemList
    * @param $mixed Site
    * @noinspection PhpDocSignatureInspection
    */
    public function filterListAtOffset(PagerProviderInterface $itemList, $mixed)
    {
        $query = $itemList->getQueryObject();
        $sort = $this->getColumnSortDirection() == 'desc' ? '<' : '>';
        $where = sprintf('t0.status %s :status', $sort);
        $query->setParameter('status', $mixed->getStatus());
        $query->andWhere($where);
    }
}
