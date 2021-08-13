<?php

namespace Olegmifle\RoutingKeyMiddleware\Tests\Unit;

use AMQPEnvelope;
use ArrayIterator;
use Olegmifle\RoutingKeyMiddleware\Middleware\RoutingKeyMiddleware;
use Olegmifle\RoutingKeyMiddleware\Tests\Stab\DummyMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Middleware\StackMiddleware;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpReceivedStamp;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;

class AddRoutingKeyMiddlewareTest extends TestCase
{
    private const TEST_ROUTING_KEY = 'test';

    public function testHandle()
    {
        $amqpEnvelope = $this->createStub(AMQPEnvelope::class);
        $amqpEnvelope->method('getRoutingKey')->willReturn(self::TEST_ROUTING_KEY);
        $amqpReceivedStamp = $this->createStub(AmqpReceivedStamp::class);
        $amqpReceivedStamp->method('getAmqpEnvelope')->willReturn($amqpEnvelope);

        $message = new DummyMessage();
        $envelope = new Envelope($message, [new AmqpStamp(self::TEST_ROUTING_KEY)]);
        $envelope = $envelope->with(new AmqpReceivedStamp($amqpEnvelope, 'qwerty'));

        $middleware = new class() implements MiddlewareInterface {
            public function handle(Envelope $envelope, StackInterface $stack): Envelope
            {
                return $stack->next()->handle($envelope, $stack);
            }
        };

        $routingKeyMiddleware = new RoutingKeyMiddleware();

        $routingKeyMiddleware->handle($envelope, new StackMiddleware(new ArrayIterator([null, $middleware])));
        $this->assertSame(self::TEST_ROUTING_KEY, $message->getRoutingKey());
    }
}
