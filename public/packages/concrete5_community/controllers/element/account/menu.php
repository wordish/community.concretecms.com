<?php
namespace Concrete\Package\Concrete5Community\Controller\Element\Account;

use Concrete\Core\Controller\ElementController;
use Concrete\Core\Url\Resolver\UrlResolverInterface;

class Menu extends ElementController
{
    
    public function getElement()
    {
        return 'account/menu';
    }

    public function view()
    {
        $this->set('logoutUrl', $this->app->make('helper/navigation')->getLogInOutLink());
    }
    
}
