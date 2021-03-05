<?php

declare(strict_types=1);

namespace PortlandLabs\Community;

use Concrete\Core\Asset\Asset;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Foundation\Service\Provider;
use PortlandLabs\Community\API\ServiceProvider as ApiServiceProvider;
use PortlandLabs\Community\Discourse\ServiceProvider as DiscourseProvider;

class ServiceProvider extends Provider
{
    protected $providers = [
        DiscourseProvider::class, // Discourse connect SSO
        ApiServiceProvider::class
    ];

    public function register()
    {
        foreach ($this->providers as $provider) {
            $this->app->make($provider)->register();
        }

        $al = AssetList::getInstance();
        $al->register("javascript", "community/teams", "js/teams.js", ["position" => Asset::ASSET_POSITION_FOOTER], "concrete_cms_community");
        $al->register("javascript", "community/karma", "js/karma.js", ["position" => Asset::ASSET_POSITION_FOOTER], "concrete_cms_community");
    }
}
