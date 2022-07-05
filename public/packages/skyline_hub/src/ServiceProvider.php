<?php

namespace PortlandLabs\Skyline;

use Concrete\Core\Foundation\Service\Provider;
use Concrete\Core\Messenger\HandlersLocator;
use Concrete\Core\Messenger\MessageBusManager;
use Concrete\Core\Messenger\Transport\Sender\DefinedTransportSendersLocator;
use Concrete\Core\Messenger\Transport\TransportManager;
use Concrete\Core\Routing\Router;
use PortlandLabs\Skyline\Command\CreateHostingSiteCommandHandler;
use PortlandLabs\Skyline\Command\TerminateHostingTrialSiteCommandHandler;
use PortlandLabs\Skyline\Controller\Stripe\Webhook;
use PortlandLabs\Skyline\Messenger\Middleware\RouteMessageToSkylineNeighborhoodMiddleware;
use PortlandLabs\Skyline\Messenger\Transport\SkylineAmqpTransport;
use Stripe\StripeClient;
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

        $this->app['manager/search_field/skyline_site'] = function ($app) {
            return $app->make('PortlandLabs\Skyline\Search\Site\Field\Manager');
        };

        $this->app->singleton(NeighborhoodListFactory::class);
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
        $transportManager->addTransport($this->app->make(SkylineAmqpTransport::class));

        /**
         * @var $messageBusManager MessageBusManager
         */
        $messageBusManager = $this->app->make(MessageBusManager::class);
        $messageBusManager->registerBus(
            'skyline_neighborhood',
            function () {
                $middleware = [
                    new AddBusNameStampMiddleware('skyline_neighborhood'),
                    new RejectRedeliveredMessageMiddleware(),
                    new DispatchAfterCurrentBusMiddleware(),
                    new FailedMessageProcessingMiddleware(),
                    new RouteMessageToSkylineNeighborhoodMiddleware(),
                    new SendMessageMiddleware(
                        $this->app->make(DefinedTransportSendersLocator::class, ['transportHandle' => 'amqp'])
                    ),
                    new HandleMessageMiddleware($this->app->make(HandlersLocator::class)),
                ];
                return new MessageBus($middleware);
            }
        );
        $messageBusManager->registerBus(
            'skyline_hub',
            function () {
                $middleware = [
                    new AddBusNameStampMiddleware('skyline_hub'),
                    new RejectRedeliveredMessageMiddleware(),
                    new DispatchAfterCurrentBusMiddleware(),
                    new FailedMessageProcessingMiddleware(),
                    new HandleMessageMiddleware($this->app->make(HandlersLocator::class)),
                ];
                return new MessageBus($middleware);
            }
        );

        $this->app->when(
            [
                CreateHostingSiteCommandHandler::class,
                TerminateHostingTrialSiteCommandHandler::class
            ]
        )
            ->needs(MessageBusInterface::class)
            ->give(
                function () use ($messageBusManager) {
                    return $messageBusManager->getBus('skyline_neighborhood');
                }
            );

        $router = $this->app->make('router');

        /**
         * Routes
         * @var $router Router
         *
         * Stripe webhook routes
         */
        $router->all('/skyline/stripe/webhook', [Webhook::class, 'receive']);

        /**
         * Search routes for Site
         */
        $router->buildGroup()->setNamespace('Concrete\Package\SkylineHub\Controller\Dialog\Site')
            ->setPrefix('/ccm/system/dialogs/skyline_site')
            ->routes('dialogs/site.php', 'skyline_hub');

        $router->buildGroup()->setNamespace('Concrete\Package\SkylineHub\Controller\Search\Site')
            ->setPrefix('/ccm/system/search/skyline_site')
            ->routes('search/site.php', 'skyline_hub');


    }
}
