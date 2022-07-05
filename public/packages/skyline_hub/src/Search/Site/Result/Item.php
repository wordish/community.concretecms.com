<?php

/**
 *
 * This file was build with the Entity Designer add-on.
 *
 * https://www.concrete5.org/marketplace/addons/entity-designer
 *
 */

/** @noinspection DuplicatedCode */

namespace PortlandLabs\Skyline\Search\Site\Result;

use PortlandLabs\Skyline\Entity\Site;
use Concrete\Core\Search\Column\Set;
use Concrete\Core\Search\Result\Item as SearchResultItem;
use Concrete\Core\Search\Result\Result as SearchResult;

class Item extends SearchResultItem
{
    public $id;
    
    public function __construct(SearchResult $result, Set $columns, $item)
    {
        parent::__construct($result, $columns, $item);
        $this->populateDetails($item);
    }
    
    /**
    * @param Site $item
    */
    protected function populateDetails($item)
    {
        $this->id = $item->getId();
    }

    public function getDetailsURL()
    {
        return \URL::to('/dashboard/skyline/sites', 'view_details', $this->item->getId());
    }
}
