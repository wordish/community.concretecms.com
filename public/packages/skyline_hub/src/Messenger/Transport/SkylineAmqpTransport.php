<?php

namespace PortlandLabs\Skyline\Messenger\Transport;

use Concrete\Controller\Backend\Marketplace\Connect;
use Concrete\Core\Application\Application;
use Concrete\Core\Messenger\Transport\TransportInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpReceiver;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpSender;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\Connection;

class SkylineAmqpTransport implements TransportInterface
{

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var string
     */
    protected $exchange;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    protected function getSenderConnection(): Connection
    {
        return Connection::fromDsn(
            $_ENV['SKYLINE_QUEUE_DSN'],
            ['auto_setup' => false, 'exchange' => ['name' => 'skyline', 'type' => 'direct']]
        );
    }

    protected function getReceiverConnection(): Connection
    {
        return Connection::fromDsn(
            $_ENV['SKYLINE_QUEUE_DSN'],
            [
                'auto_setup' => false,
                'queues' => [
                    'hub' => ['hub'],
                ],
            ]
        );
    }

    public function getSenders(): iterable
    {
        $app = $this->app;
        return [
            'amqp' => function () use ($app) {
                return $app->make(
                    AmqpSender::class,
                    [
                        'connection' => $this->getSenderConnection()
                    ]
                );
            }
        ];
    }

    public function getReceivers(): iterable
    {
        $app = $this->app;
        return [
            'skyline_hub' => function () use ($app) {
                return $app->make(
                    AmqpReceiver::class,
                    [
                        'connection' => $this->getReceiverConnection()
                    ]
                );
            }
        ];
    }

}