<?php

declare(strict_types=1);

namespace PortlandLabs\Hosting;

use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Foundation\Service\Provider;

class ServiceProvider extends Provider
{
    protected $providers = [
    ];

    public function register()
    {
        $al = AssetList::getInstance();
        $al->register("javascript", "package/hosting/frontend", "js/main.js", ["position" => Asset::ASSET_POSITION_FOOTER], "concrete_cms_hosting");

        foreach ($this->providers as $provider) {
            $this->app->make($provider)->register();
        }

        $this->app->make('director')->addListener('on_before_render', function ($event) {
            // we have to use on before render because it fires after the page theme require asset, which we need
            // to fire first so it add's vue to the page.
            $view = $event->getArgument("view");
            if ($view) {
                $view->requireAsset('javascript', 'package/hosting/frontend');
            }
        });


    }
}
