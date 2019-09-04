<?php

namespace Concrete5\Community\Lambda;

use Concrete\Core\Cache\CacheServiceProvider;
use Concrete\Core\Config\ConfigServiceProvider;
use Concrete\Core\Database\DatabaseServiceProvider;
use Concrete\Core\Database\EntityManagerConfigUpdater;
use Concrete\Core\Events\EventsServiceProvider;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Support\Facade\Config;
use Concrete\Core\Support\Facade\Events;
use Concrete\Core\Url\Resolver\Manager\ResolverManager;
use Concrete\Core\Url\Resolver\Manager\ResolverManagerInterface;
use Concrete\Package\Concrete5Community\Controller;
use Doctrine\ORM\EntityManager;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Run;

class ServiceProvider extends Provider
{

    /**
     * Registers the services provided by this provider.
     */
    public function register()
    {
        $app = $this->app;
        $app->instance('app', $app);

        $whoops = new Run();
        $whoops->appendHandler($app->make(JsonResponseHandler::class));
        $whoops->register();

        // Make facades work
        Events::setFacadeApplication($app);

        // I hate class aliases
        class_alias(Events::class, \Events::class);
        class_alias(Application::class, \Core::class);
        class_alias(Config::class, \Config::class);

        // Register the core service providers we're going to need
        $this->app->make(ConfigServiceProvider::class)->register();
        $this->app['config']['concrete.cache.enabled'] = false;

        $this->app->make(EventsServiceProvider::class)->register();
        $this->app->make(DatabaseServiceProvider::class)->register();
        $this->app->make(CacheServiceProvider::class)->register();

        $app->bind(ResolverManagerInterface::class, ResolverManager::class);

        // Make custom entities work
        $this->app->extend(EntityManager::class, function (EntityManager $entityManager) {
            $updater = $this->app->make(EntityManagerConfigUpdater::class, [
                'entityManager' => $entityManager
            ]);

            require_once __DIR__ . '/../../controller.php';
            $package = $this->app->make(Controller::class);
            $updater->addProvider($package->getEntityManagerProvider());

            return $entityManager;
        });

    }
}
