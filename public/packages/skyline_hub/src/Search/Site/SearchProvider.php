<?php

/**
 *
 * This file was build with the Entity Designer add-on.
 *
 * https://www.concrete5.org/marketplace/addons/entity-designer
 *
 */

/** @noinspection DuplicatedCode */
/** @noinspection PhpUnused */

namespace PortlandLabs\Skyline\Search\Site;

use PortlandLabs\Skyline\Entity\Search\SavedSiteSearch;
use PortlandLabs\Skyline\Site\SiteList;
use PortlandLabs\Skyline\Search\Site\ColumnSet\DefaultSet;
use PortlandLabs\Skyline\Search\Site\ColumnSet\Available;
use PortlandLabs\Skyline\Search\Site\ColumnSet\ColumnSet;
use PortlandLabs\Skyline\Search\Site\Result\Result;
use Concrete\Core\Search\AbstractSearchProvider;
use Concrete\Core\Search\Field\ManagerFactory;

class SearchProvider extends AbstractSearchProvider
{
    public function getFieldManager()
    {
        return ManagerFactory::get('hosting_site');
    }
    
    public function getSessionNamespace()
    {
        return 'hosting_site';
    }
    
    public function getCustomAttributeKeys()
    {
        return [];
    }
    
    public function getBaseColumnSet()
    {
        return new ColumnSet();
    }
    
    public function getAvailableColumnSet()
    {
        return new Available();
    }
    
    public function getCurrentColumnSet()
    {
        return ColumnSet::getCurrent();
    }
    
    public function createSearchResultObject($columns, $list)
    {
        return new Result($columns, $list);
    }
    
    public function getItemList()
    {
        return new SiteList();
    }
    
    public function getDefaultColumnSet()
    {
        return new DefaultSet();
    }
    
    public function getSavedSearch()
    {
        return new SavedHostingSiteSearch();
    }
}
