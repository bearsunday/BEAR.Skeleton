<?php

declare(strict_types=1);

namespace BEAR\Skeleton;

use BEAR\Package\Injector as PackageInjector;
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

    public static function getInstance(string $context): InjectorInterface
    {
        return PackageInjector::getInstance(__NAMESPACE__, $context, dirname(__DIR__));
    }
}
