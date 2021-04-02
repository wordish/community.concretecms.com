<?php

namespace PortlandLabs\Hosting\Api;

use Concrete\Core\Support\Facade\Session;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
use PortlandLabs\Hosting\Api\Client\Client;

class ApiServiceProvider extends \Concrete\Core\Foundation\Service\Provider
{

    public function register()
    {
        $this->app->singleton(Client::class);
        $this->app
            ->when(Client::class)
            ->needs(GuzzleClient::class)
            ->give(function() {
                $token = $this->app->make(Token::class);
                if (!(string) $token) {
                    $token->renew(new GuzzleClient());
                }

                return new GuzzleClient([
                    'baseUrl' => $_ENV['CONCRETE_API_URL'],
                    RequestOptions::TIMEOUT => 5,
                    RequestOptions::HEADERS => [
                        'Authorization' => (string) $token
                    ]
                ]);
            });
    }
}
