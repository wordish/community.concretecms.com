<?php

namespace PortlandLabs\Community\Package\ItemCategory;

use Concrete\Core\Entity\Package;
use Concrete\Core\Package\ItemCategory\AbstractCategory;
use PortlandLabs\Community\User\Point\Action\Action;

class UserPointAction extends AbstractCategory
{
    public function getItemCategoryDisplayName()
    {
        return t('User Point Actions');
    }

    /**
     * @param Action $action
     * @return mixed
     */
    public function getItemName($action)
    {
        return $action->getUserPointActionName();
    }

    public function getPackageItems(Package $package)
    {
        return Action::getListByPackage($package);
    }
}
