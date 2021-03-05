<?php

/**
 * @project:   ConcreteCMS Community
 *
 * @copyright  (C) 2021 Portland Labs (https://www.portlandlabs.com)
 * @author     Fabian Bitter (fabian@bitter.de)
 */

namespace PortlandLabs\Community\Page\Controller;

use Concrete\Core\Error\ErrorList\ErrorList;
use Concrete\Core\Http\ResponseFactory;
use Concrete\Core\Page\Controller\AccountPageController as CoreAccountPageController;
use Concrete\Core\Page\Theme\Theme;

class AccountPageController extends CoreAccountPageController
{
    /** @var ErrorList */
    protected $error;
    /** @var ResponseFactory */
    protected $responseFactory;

    public function on_start()
    {
        parent::on_start();

        if (is_object(Theme::getByHandle("concrete_cms"))) {
            // Override the theme
            $this->setTheme('concrete_cms');
        }

        $this->set('exclude_breadcrumb', true);

        $this->responseFactory = $this->app->make(ResponseFactory::class);
    }
}