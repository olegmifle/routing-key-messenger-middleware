# Add Routing Key to message middleware

A simple middleware to add routing key to message.

## Dependencies
* php >= 7.4
* symfony/messenger >= 4.0

## Installation
```bash
composer require olegmifle/routing-key-messenger-middleware
```
# Usage
Configure this Middleware to your MessageBus

#### Configure Middleware

```yaml
framework:
    messenger:
        buses:
            message.bus.commands:
                middleware:
                    - 'RoutingKeyMiddleware\Middleware\RoutingKeyMiddleware'
```

### Configure your message

```php
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use RoutingKeyMiddleware\Middleware\AddRoutingKeyInterface;

class YourAwesomeMessage implements MessageHandlerInterface, AddRoutingKeyInterface
{
    private ?string $routingKey;
    
    public function setRoutingKey(?string $routingKey): self
    {
       $this->routingKey = $routingKey;
        
        return $this;
    }

    public function getRoutingKey(): ?string
    {
        return $this->routingKey;
    }
}
```