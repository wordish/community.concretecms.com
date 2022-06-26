<?php

namespace PortlandLabs\Skyline;

use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Messenger\Transport\TransportManager;
use Stripe\StripeClient;
use PortlandLabs\Skyline\Messenger\Transport\AmqpTransport;

class ServiceProvider extends Provider
{

    public function register()
    {
        $this->app->singleton(
            StripeClient::class,
            function () {
                $stripe = new StripeClient(
                    [
                        'api_key' => $_ENV['SKYLINE_STRIPE_SECRET_KEY']
                    ]
                );
                return $stripe;
            }
        );

        $transportManager = $this->app->make(TransportManager::class);
        /**
         * @var $transportManager TransportManager
         */
        $transportManager->addTransport($this->app->make(AmqpTransport::class));
    }
}
