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
use PortlandLabs\Skyline\Search\Site\ColumnSet\Column\IdColumn;
use PortlandLabs\Skyline\Search\Site\ColumnSet\Column\NameColumn;
use PortlandLabs\Skyline\Search\Site\ColumnSet\Column\HandleColumn;
use PortlandLabs\Skyline\Search\Site\ColumnSet\Column\SubscriptionIdColumn;
use PortlandLabs\Skyline\Search\Site\ColumnSet\Column\SubscriptionStatusColumn;
use PortlandLabs\Skyline\Search\Site\ColumnSet\Column\NeighborhoodColumn;
use PortlandLabs\Skyline\Search\Site\ColumnSet\Column\AdminPasswordColumn;
use PortlandLabs\Skyline\Search\Site\ColumnSet\Column\StatusColumn;
use PortlandLabs\Skyline\Search\Site\ColumnSet\Column\SuspendedTimestampColumn;

class Available extends ColumnSet
{

    public function __construct()
    {
        $this->addColumn(new IdColumn());
        $this->addColumn(new NameColumn());
        $this->addColumn(new HandleColumn());
        $this->addColumn(new SubscriptionIdColumn());
        $this->addColumn(new SubscriptionStatusColumn());
        $this->addColumn(new NeighborhoodColumn());
        $this->addColumn(new AdminPasswordColumn());
        $this->addColumn(new StatusColumn());
        $this->addColumn(new SuspendedTimestampColumn());

        $name = $this->getColumnByKey('t0.name');
        $this->setDefaultSortColumn($name, 'asc');
    }

}
