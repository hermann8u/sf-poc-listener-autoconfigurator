<?php

declare(strict_types=1);

namespace FHermann\ListenerAutoconfiguratorBundle\DependencyInjection;

use FHermann\ListenerAutoconfiguratorBundle\EventListenerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class ListenerAutoconfiguratorExtension extends Extension
{
    public const BUNDLE_LISTENER_TAG = 'listener_autoconfigurator.event_listener';

    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(EventListenerInterface::class)
            ->addTag(self::BUNDLE_LISTENER_TAG)
        ;
    }
}
