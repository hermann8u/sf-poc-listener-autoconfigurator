<?php

declare(strict_types=1);

namespace FHermann\ListenerAutoconfiguratorBundle;

interface PriorizableEventListenerInterface extends EventListenerInterface
{
    public static function getPriority(): int;
}
