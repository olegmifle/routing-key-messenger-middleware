<?php

declare(strict_types=1);

namespace Olegmifle\RoutingKeyMiddleware\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpReceivedStamp;

final class RoutingKeyMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $stamp = $envelope->last(AmqpReceivedStamp::class);
        $message = $envelope->getMessage();

        if ($stamp !== null && $message instanceof AddRoutingKeyInterface) {
            $message->setRoutingKey($stamp->getAmqpEnvelope()->getRoutingKey());
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
