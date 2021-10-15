<?php

declare(strict_types=1);

namespace BEAR\Skeleton;

use BEAR\Package\Injector as PackageInjector;
use Ray\Di\AbstractModule;
use Ray\Di\InjectorInterface;

use function dirname;

final class Injector
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public static function getInstance(string $context): InjectorInterface
    {
        return PackageInjector::getInstance(__NAMESPACE__, $context, dirname(__DIR__));
    }

    public static function getOverrideInstance(string $context, AbstractModule $overrideModule): InjectorInterface
    {
        return PackageInjector::getOverrideInstance(__NAMESPACE__, $context, dirname(__DIR__), $overrideModule);
    }
}
