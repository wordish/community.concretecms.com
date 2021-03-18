<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting;

use Concrete\Core\Application\Application;
use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Foundation\Service\Provider;
use PortlandLabs\Hosting\Api\Client\Denormalizer;
use PortlandLabs\Hosting\Project\Project;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ServiceProvider extends Provider
{
    protected $providers = [
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
        
        $this->app->singleton(Denormalizer::class, function($app) {
            $serializer = new Serializer(
                [
                    new JsonSerializableNormalizer(),
                    new CustomNormalizer(),
                    new GetSetMethodNormalizer()
                ], [
                    new JsonEncoder()
                ]
            );
            $denormalizer = new Denormalizer($serializer);
            $denormalizer->registerMapping('Project', Project::class);
            return $denormalizer;
        });

    }
}
