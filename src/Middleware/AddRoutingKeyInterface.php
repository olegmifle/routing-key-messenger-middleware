<?php

declare(strict_types=1);

namespace Olegmifle\RoutingKeyMiddleware\Middleware;

interface AddRoutingKeyInterface
{
    public function setRoutingKey(?string $routingKey): self;

    public function getRoutingKey(): ?string;
}
