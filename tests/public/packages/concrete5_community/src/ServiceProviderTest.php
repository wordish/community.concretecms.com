<?php

namespace Concrete5\Community;

use Concrete\Core\Application\Application;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Url\Resolver\Manager\ResolverManager;
use Concrete5\Community\Integration\Stackoverflow\StackoverflowService;
use Mockery;
use OAuth\ServiceFactory;

class ServiceProviderTest extends TestCase
{

    public function testRegister(): void
    {
        $app = Mockery::mock(Application::class);

        // Make sure we see our stuff get bound as we expect
        $app->shouldReceive('extend')->withSomeOfArgs(ServiceFactory::class)->once();
        $app->shouldReceive('bind')->withSomeOfArgs(StackoverflowService::class)->once();

        $repository = Mockery::mock(Repository::class);
        $resolver = Mockery::mock(ResolverManager::class);

        $provder = new ServiceProvider($app, $repository, $resolver);
        $provder->register();
    }
}
