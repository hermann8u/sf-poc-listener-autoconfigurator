# Autoconfigure your event listeners in Symfony

This repository contains a POC of a Symfony bundle to autoconfigure event listener.

## Usage

Imagine this listener :

``` php
<?php

declare(strict_types=1);

namespace App\Event\Listener;

use App\Event\UserCreated;
use FHermann\ListenerAutoconfiguratorBundle\EventListenerInterface;

final class UserCreatedListener implements EventListenerInterface
{
    public function __invoke(UserCreated $event): void
    {
        // Do whatever when a User is created
    }
}
```

The bundle will be able to create this equivalent config for you :

``` yaml
# config/services.yaml
services:
    App\Event\Listener\UserCreatedListener:
        tags:
            - { name: kernel.event_listener, event: App\Event\UserCreated }
```

### Add priority

If you want to be able to add a priority for the previous listener, you can implement the PriorizableEventListenerInterface :

``` php
<?php

declare(strict_types=1);

namespace App\Event\Listener;

use App\Event\UserCreated;
use FHermann\ListenerAutoconfiguratorBundle\PriorizableEventListenerInterface;

final class UserCreatedListener implements PriorizableEventListenerInterface
{
    public function __invoke(UserCreated $event): void
    {
        // Do whatever when a User is created
    }

    public static function getPriority(): int
    {
        return 125;
    }
}
```
