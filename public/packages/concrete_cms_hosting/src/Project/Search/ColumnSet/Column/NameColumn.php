<?php

namespace PortlandLabs\Hosting\Project\Search\ColumnSet\Column;

use Concrete\Core\Search\Column\Column;

class NameColumn extends Column
{

    public function getColumnKey()
    {
        return 'name';
    }

    public function getColumnName()
    {
        return t('Name');
    }

    public function getColumnCallback()
    {
        return 'getName';
    }

}
