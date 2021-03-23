<?php

namespace PortlandLabs\Hosting\Project\Search\ColumnSet\Column;

use Concrete\Core\Search\Column\Column;

class ProjectIdentifierColumn extends Column
{

    public function getColumnKey()
    {
        return 'id';
    }

    public function getColumnName()
    {
        return t('ID');
    }

    public function getColumnCallback()
    {
        return 'getId';
    }

}
