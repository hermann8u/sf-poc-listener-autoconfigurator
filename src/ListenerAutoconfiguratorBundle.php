<?php

declare(strict_types=1);

namespace FHermann\ListenerAutoconfiguratorBundle;

use FHermann\ListenerAutoconfiguratorBundle\DependencyInjection\Compiler\RegisterListenerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ListenerAutoconfiguratorBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterListenerPass());
    }
}
