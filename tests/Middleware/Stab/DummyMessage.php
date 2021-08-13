<?php


namespace Olegmifle\RoutingKeyMiddleware\Tests\Stab;


use Olegmifle\RoutingKeyMiddleware\Middleware\AddRoutingKeyInterface;

class DummyMessage implements AddRoutingKeyInterface
{
    private string $routingKey;

    public function setRoutingKey(?string $routingKey): AddRoutingKeyInterface
    {
        $this->routingKey = $routingKey;

        return $this;
    }

    public function getRoutingKey(): ?string
    {
        return $this->routingKey;
    }

}
