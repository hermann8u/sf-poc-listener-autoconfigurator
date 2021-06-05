<?php

declare(strict_types=1);

namespace Acme\AcmeBundle\Tests;

use FHermann\ListenerAutoconfiguratorBundle\ListenerAutoconfiguratorBundle;
use PHPUnit\Framework\TestCase;

class AcmeBundleTest extends TestCase
{
    public function testGetPath(): void
    {
        self::assertSame(\dirname(__DIR__), (new ListenerAutoconfiguratorBundle())->getPath());
    }
}
