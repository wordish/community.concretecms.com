<?php

namespace Concrete\Package\SkylineClient\Controller\SinglePage\Dashboard;

use Concrete\Core\Page\Controller\DashboardPageController;

class Skyline extends DashboardPageController
{

    public function view()
    {
        return $this->buildRedirectToFirstAccessibleChildPage();
    }

}
