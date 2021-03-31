<?php

namespace PortlandLabs\Hosting\Project\Search\ColumnSet\Column;

use Concrete\Core\Search\Column\Column;
use Concrete\Core\User\UserInfoRepository;
use PortlandLabs\Hosting\Project\Project;

class OwnerColumn extends Column
{

    public function isColumnSortable()
    {
        return false;
    }

    public function getColumnKey()
    {
        return 'userId';
    }

    public function getColumnName()
    {
        return t('Owner');
    }

    public function getColumnCallback()
    {
        return 'getUserDisplayName';
    }
}
