<?php

namespace PortlandLabs\Skyline;

use Concrete\Core\Application\Application;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Messenger\HandlersLocator;
use Concrete\Core\Messenger\MessageBusManager;
use Concrete\Core\Messenger\Transport\Sender\DefinedTransportSendersLocator;
use Concrete\Core\Messenger\Transport\TransportManager;
use PortlandLabs\Skyline\Command\CreateHostingSiteCommandHandler;
use Stripe\StripeClient;
use PortlandLabs\Skyline\Messenger\Transport\AmqpTransport;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\AddBusNameStampMiddleware;
use Symfony\Component\Messenger\Middleware\DispatchAfterCurrentBusMiddleware;
use Symfony\Component\Messenger\Middleware\FailedMessageProcessingMiddleware;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Middleware\RejectRedeliveredMessageMiddleware;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;

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

        /**
         * @var $messageBusManager MessageBusManager
         */
        $messageBusManager = $this->app->make(MessageBusManager::class);
        $messageBusManager->registerBus('skyline', function() {
            $middleware = [
                new AddBusNameStampMiddleware('skyline'),
                new RejectRedeliveredMessageMiddleware(),
                new DispatchAfterCurrentBusMiddleware(),
                new FailedMessageProcessingMiddleware(),
                new SendMessageMiddleware($this->app->make(DefinedTransportSendersLocator::class, ['transportHandle' => 'amqp'])),
                new HandleMessageMiddleware($this->app->make(HandlersLocator::class)),
            ];
            return new MessageBus($middleware);
        });

        $this->app->when(CreateHostingSiteCommandHandler::class)
            ->needs(MessageBusInterface::class)
            ->give(function() use ($messageBusManager) {
                return $messageBusManager->getBus('skyline');
            });
    }
}
