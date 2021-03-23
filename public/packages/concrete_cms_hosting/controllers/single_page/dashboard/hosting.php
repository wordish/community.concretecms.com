<?php

namespace Concrete\Package\ConcreteCmsHosting\Controller\SinglePage\Dashboard;

use Concrete\Core\Page\Controller\DashboardPageController;

class Hosting extends DashboardPageController
{

    public function view()
    {
        return $this->buildRedirectToFirstAccessibleChildPage();
    }

}
