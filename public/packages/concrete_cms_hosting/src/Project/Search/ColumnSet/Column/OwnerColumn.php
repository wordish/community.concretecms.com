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

    /**
     * @param Project $project
     * @return string
     */
    public function getColumnValue($project)
    {
        /**
         * @var $repository UserInfoRepository
         */
        $repository = app(UserInfoRepository::class);
        $user = null;
        if ($project->getUserId()) {
            $user = $repository->getByID($project->getUserId());
        }
        if ($user) {
            return $user->getUserDisplayName();
        } else {
            return t('(Not Available.');
        }
    }

}
