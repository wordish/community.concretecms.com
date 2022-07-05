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

class DateCreatedColumn extends Column implements PagerColumnInterface
{
    public function getColumnKey()
    {
        return 't0.dateCreated';
    }
    
    public function getColumnName()
    {
        return t('Date Created');
    }

    /**
     * @param Site
     * @return string
     */
    public function getColumnValue($mixed)
    {
        return $mixed->getDateCreated('F d, Y');
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
        $where = sprintf('t0.dateCreated %s :dateCreated', $sort);
        $query->setParameter('dateCreated', $mixed->getDateCreated());
        $query->andWhere($where);
    }
}
