<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting;

use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Foundation\Command\DispatcherFactory;
use Concrete\Core\Foundation\Service\Provider;
use PortlandLabs\Hosting\Api\ApiServiceProvider;
use PortlandLabs\Hosting\Api\Client\Denormalizer;
use PortlandLabs\Hosting\Project\LagoonProject;
use PortlandLabs\Hosting\Project\Project;
use PortlandLabs\Hosting\Serializer\Serializer;
use PortlandLabs\Hosting\Software\ConcreteRelease;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

class ServiceProvider extends Provider
{
    protected array $providers = [
        ApiServiceProvider::class
    ];

    public function register()
    {
        $al = AssetList::getInstance();
        $al->register(
            "javascript",
            "package/hosting/frontend",
            "js/main.js",
            ["position" => Asset::ASSET_POSITION_FOOTER],
            "concrete_cms_hosting"
        );

        foreach ($this->providers as $provider) {
            $this->app->make($provider)->register();
        }

        $this->app->make('director')->addListener(
            'on_before_render',
            function ($event) {
                // we have to use on before render because it fires after the page theme require asset, which we need
                // to fire first so it add's vue to the page.
                $view = $event->getArgument("view");
                if ($view) {
                    $view->requireAsset('javascript', 'package/hosting/frontend');
                }
            }
        );

        $this->app->singleton(Serializer::class, function($app) {
            $serializer = new Serializer(
                [
                    new JsonSerializableNormalizer(),
                    new CustomNormalizer(),
                    new GetSetMethodNormalizer(),
                    new PropertyNormalizer()
                ], [
                    new JsonEncoder()
                ]
            );
            return $serializer;
        });

        $this->app->singleton(Denormalizer::class, function($app) {
            $serializer = $this->app->make(Serializer::class);
            $denormalizer = new Denormalizer($serializer);
            $denormalizer->registerMapping('Project', Project::class);
            $denormalizer->registerMapping('LagoonProject', LagoonProject::class);
            $denormalizer->registerMapping('ConcreteRelease', ConcreteRelease::class);
            return $denormalizer;
        });

        $this->app->extend(DispatcherFactory::class, function($factory) {
            $dispatcher = $factory->getDispatcher();
            foreach ([
                         ['PortlandLabs\Hosting\Project\Command\DeleteProjectCommand', 'PortlandLabs\Hosting\Project\Command\DeleteProjectCommandHandler'],
                         ['PortlandLabs\Hosting\Project\Command\CreateProjectCommand', 'PortlandLabs\Hosting\Project\Command\CreateProjectCommandHandler'],
                         ['PortlandLabs\Hosting\Project\Command\CreateLagoonProjectCommand', 'PortlandLabs\Hosting\Project\Command\CreateLagoonProjectCommandHandler'],
                         ['PortlandLabs\Hosting\Project\Command\UpdateProjectCommand', 'PortlandLabs\Hosting\Project\Command\UpdateProjectCommandHandler'],
                         ['PortlandLabs\Hosting\Project\Command\UpdateLagoonProjectCommand', 'PortlandLabs\Hosting\Project\Command\UpdateLagoonProjectCommandHandler'],
                         ['PortlandLabs\Hosting\Software\Command\DeleteConcreteReleaseCommand', 'PortlandLabs\Hosting\Software\Command\DeleteConcreteReleaseCommandHandler'],
                         ['PortlandLabs\Hosting\Software\Command\CreateConcreteReleaseCommand', 'PortlandLabs\Hosting\Software\Command\CreateConcreteReleaseCommandHandler'],
                         ['PortlandLabs\Hosting\Software\Command\UpdateConcreteReleaseCommand', 'PortlandLabs\Hosting\Software\Command\UpdateConcreteReleaseCommandHandler'],
                     ] as $entry) {
                $command = $entry[0];
                $handler = $entry[1];
                $dispatcher->registerCommand($this->app->make($handler), $command);

            }

            return $factory;
        });
    }
}
