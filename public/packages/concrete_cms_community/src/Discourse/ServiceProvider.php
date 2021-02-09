<?php

declare(strict_types=1);

namespace PortlandLabs\Community\Discourse;

use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Routing\Router;
use PortlandLabs\Community\Discourse\Connect\ConnectController;

class ServiceProvider extends Provider
{

    public function register()
    {
        $router = $this->app->make(Router::class);
        $router->buildGroup()
            ->setPrefix('ccm/api/v1/discourse')
            ->scope('discourse_connect')
            ->routes(fn(Router $router) => $this->routes($router), 'concrete_cms_community');
    }

    private function routes(Router $router)
    {
        // ccm/api/v1/discourse/connect
        $router->get('connect', ConnectController::class . '::connect');
    }
}
