<?php

namespace PortlandLabs\Hosting\Project\Search\ColumnSet\Column;

use Concrete\Core\Search\Column\Column;
use PortlandLabs\Hosting\Project\Project;

class DateCreatedColumn extends Column
{

    public function getColumnKey()
    {
        return 'dateCreated';
    }

    public function getColumnName()
    {
        return t('Date Created');
    }

    public function getColumnCallback()
    {
        return 'getDateCreatedString';
    }
}
