<?php

namespace Concrete\Package\SkylineHub\Controller\SinglePage\Dashboard\Skyline;

use Concrete\Core\Controller\Traits\DashboardExpressEntityTrait;
use Concrete\Core\Entity\Express\Entity;
use Concrete\Core\Page\Controller\DashboardSitePageController;

class Sites extends DashboardSitePageController
{

    use DashboardExpressEntityTrait;

    public function getExpressEntity(): Entity
    {
        return $this->app->make('express')->getObjectByHandle('skyline_hosting_site');
    }


}
