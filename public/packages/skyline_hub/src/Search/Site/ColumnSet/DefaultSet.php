<?php

/**
 *
 * This file was build with the Entity Designer add-on.
 *
 * https://www.concrete5.org/marketplace/addons/entity-designer
 *
 */

/** @noinspection DuplicatedCode */

namespace PortlandLabs\Skyline\Search\Site\ColumnSet;

use Concrete\Core\Search\Column\Column;
use PortlandLabs\Skyline\Search\Site\ColumnSet\Column\DateCreatedColumn;
use PortlandLabs\Skyline\Search\Site\ColumnSet\Column\HandleColumn;
use PortlandLabs\Skyline\Search\Site\ColumnSet\Column\NameColumn;
use PortlandLabs\Skyline\Search\Site\ColumnSet\Column\NeighborhoodColumn;

class DefaultSet extends ColumnSet
{

    public function __construct()
    {
        $this->addColumn(new NameColumn());
        $this->addColumn(new HandleColumn());
        $this->addColumn(new NeighborhoodColumn());
        $this->addColumn(new DateCreatedColumn());

        $date = $this->getColumnByKey('t0.dateCreated');
        $this->setDefaultSortColumn($date, 'desc');

    }
}
