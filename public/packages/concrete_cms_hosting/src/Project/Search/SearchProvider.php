<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting\Project\Search;
use Concrete\Core\Logging\LogList;
use Concrete\Core\Search\AbstractSearchProvider;
use PortlandLabs\Hosting\Project\ProjectList;
use PortlandLabs\Hosting\Project\Search\ColumnSet\DefaultSet;
use PortlandLabs\Hosting\Project\Search\Result\Result;

class SearchProvider extends AbstractSearchProvider
{

    public function getFieldManager()
    {
        return null;
    }

    public function getSessionNamespace()
    {
        return null;
    }

    public function getCustomAttributeKeys()
    {
        return [];
    }

    public function getBaseColumnSet()
    {
        return null;
    }

    public function getAvailableColumnSet()
    {
        return null;
    }

    public function getCurrentColumnSet()
    {
        return null;
    }

    public function createSearchResultObject($columns, $list)
    {
        return new Result($columns, $list);
    }

    public function getItemList()
    {
        return app(ProjectList::class);
    }

    public function getDefaultColumnSet()
    {
        return new DefaultSet();
    }

    public function getSavedSearch()
    {
        return null;
    }

}
