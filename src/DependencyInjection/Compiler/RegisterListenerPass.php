<?php

declare(strict_types=1);

namespace FHermann\ListenerAutoconfiguratorBundle\DependencyInjection\Compiler;

use FHermann\ListenerAutoconfiguratorBundle\DependencyInjection\ListenerAutoconfiguratorExtension;
use FHermann\ListenerAutoconfiguratorBundle\PriorizableEventListenerInterface;
use ReflectionClass;
use ReflectionNamedType;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use function array_keys;
use function class_implements;
use function in_array;

final class RegisterListenerPass implements CompilerPassInterface
{
    private const SYMFONY_EVENT_LISTENER_TAG = 'kernel.event_listener';
    private const INVOKE_METHOD = '__invoke';

    public function process(ContainerBuilder $container): void
    {
        /** @var string[] $serviceIds */
        $serviceIds = array_keys($container->findTaggedServiceIds(ListenerAutoconfiguratorExtension::BUNDLE_LISTENER_TAG));

        foreach ($serviceIds as $serviceId) {
            $definition = $container->getDefinition($serviceId);

            $definition->addTag(self::SYMFONY_EVENT_LISTENER_TAG, $this->getTagAttributes($definition));
        }
    }

    private function getTagAttributes(Definition $definition): array
    {
        /** @var class-string $listenerFQCN */
        $listenerFQCN = $definition->getClass();

        return [
            'event' => $this->getEvent($listenerFQCN),
            'method' => self::INVOKE_METHOD,
            'priority' => $this->getPriority($listenerFQCN)
        ];
    }

    /**
     * @param class-string $listenerFQCN
     *
     * @return class-string
     */
    private function getEvent(string $listenerFQCN): string
    {
        $reflectionClass = new ReflectionClass($listenerFQCN);
        $reflectionInvoke = $reflectionClass->getMethod(self::INVOKE_METHOD);
        $reflectionParameter = $reflectionInvoke->getParameters()[0] ?? null;
        if ($reflectionParameter === null) {
            throw new \Exception('No parameter');
        }

        $reflectionType = $reflectionParameter->getType();
        if ($reflectionType === null) {
            throw new \Exception('No typehint for listener parameter');
        }

        if ($reflectionType instanceof ReflectionNamedType === false) {
            throw new \Exception('Union type not supported');
        }

        return $reflectionType->getName();
    }

    /**
     * @param class-string $listenerFQCN
     *
     * @return int
     */
    private function getPriority(string $listenerFQCN): int
    {
        if (in_array(PriorizableEventListenerInterface::class, class_implements($listenerFQCN)) === false) {
            return 0;
        }

        /** @var class-string<PriorizableEventListenerInterface> $listenerFQCN */
        return $listenerFQCN::getPriority();
    }
}
