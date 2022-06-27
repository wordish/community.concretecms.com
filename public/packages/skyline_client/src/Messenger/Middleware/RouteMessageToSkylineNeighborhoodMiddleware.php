<?php

namespace PortlandLabs\Skyline\Messenger\Middleware;

use PortlandLabs\Skyline\Command\NeighborhoodAwareInterface;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

class RouteMessageToSkylineNeighborhoodMiddleware implements MiddlewareInterface
{

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (null === $envelope->last(AmqpStamp::class)) {
            $message = $envelope->getMessage();
            if ($message instanceof NeighborhoodAwareInterface) {
                $envelope = $envelope->with(new AmqpStamp($message->getNeighborhood()));
            }
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
