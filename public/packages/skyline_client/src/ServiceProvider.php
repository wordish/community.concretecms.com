<?php

namespace PortlandLabs\Skyline;

use Concrete\Core\Foundation\Service\Provider;
use Stripe\StripeClient;

class ServiceProvider extends Provider
{

    public function register()
    {
        $this->app->singleton(StripeClient::class, function() {
            $stripe = new StripeClient([
                'api_key' => $_ENV['SKYLINE_STRIPE_SECRET_KEY']
            ]);
            return $stripe;
        });
    }

}
