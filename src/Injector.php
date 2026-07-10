<?php

declare(strict_types=1);

namespace BEAR\Skeleton;

use BEAR\AppMeta\Meta;
use BEAR\Package\Injector\PackageInjector;
use Ray\Di\AbstractModule;
use Ray\Di\InjectorInterface;
use Ray\PsrCacheModule\LocalCacheProvider;

use function dirname;
use function str_replace;

/** @SuppressWarnings("PHPMD.StaticAccess") */
final class Injector
{
    /** @codeCoverageIgnore */
    private function __construct()
    {
    }

    /** @param non-empty-string $context */
    public static function getInstance(
        string $context,
        string|null $tmpDir = null,
        string|null $logDir = null,
    ): InjectorInterface {
        $meta = new Meta(__NAMESPACE__, $context, dirname(__DIR__), $tmpDir, $logDir);
        $cacheNamespace = str_replace('/', '_', $meta->appDir) . $context;
        $cache = (new LocalCacheProvider($meta->tmpDir . '/injector', $cacheNamespace))->get();

        return PackageInjector::getInstance($meta, $context, $cache);
    }

    /** @param non-empty-string $context */
    public static function getOverrideInstance(string $context, AbstractModule $overrideModule): InjectorInterface
    {
        $meta = new Meta(__NAMESPACE__, $context, dirname(__DIR__));

        return PackageInjector::factory($meta, $context, $overrideModule);
    }
}
