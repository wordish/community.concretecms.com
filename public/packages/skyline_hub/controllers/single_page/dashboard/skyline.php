<?php

namespace Concrete\Package\SkylineHub\Controller\SinglePage\Dashboard;

use Concrete\Core\Page\Controller\DashboardPageController;

class Skyline extends DashboardPageController
{

    public function view()
    {
        return $this->buildRedirectToFirstAccessibleChildPage();
    }

}
