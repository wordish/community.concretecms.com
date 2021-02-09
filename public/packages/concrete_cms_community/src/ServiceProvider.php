<?php

declare(strict_types=1);

namespace PortlandLabs\Community;

use Concrete\Core\Foundation\Service\Provider;
use PortlandLabs\Community\Discourse\ServiceProvider as DiscourseProvider;

class ServiceProvider extends Provider
{
    protected $providers = [
        DiscourseProvider::class // Discourse connect SSO
    ];

    public function register()
    {
        foreach ($this->providers as $provider) {
            $this->app->make($provider)->register();
        }
    }
}
