<?php

namespace PortlandLabs\Hosting\Project\Search\ColumnSet;

use PortlandLabs\Hosting\Project\Search\ColumnSet\Column\NameColumn;
use PortlandLabs\Hosting\Project\Search\ColumnSet\Column\ProjectIdentifierColumn;

class DefaultSet extends ColumnSet
{

    public function __construct()
    {
        $this->addColumn(new ProjectIdentifierColumn());
        $this->addColumn(new NameColumn());

        $date = $this->getColumnByKey('id');
        $this->setDefaultSortColumn($date, 'desc');
    }
}
