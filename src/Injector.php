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
        string|null $tmpDir = null, // ={appDir}/var/tmp/{context}
        string|null $logDir = null, // ={appDir}/var/log/{context}
    ): InjectorInterface {
        $meta = self::newMeta($context, $tmpDir, $logDir);
        $cacheNamespace = str_replace('/', '_', $meta->appDir) . $context;
        $cache = (new LocalCacheProvider($meta->tmpDir . '/injector', $cacheNamespace))->get();

        return PackageInjector::getInstance($meta, $context, $cache);
    }

    /** @param non-empty-string $context */
    public static function getOverrideInstance(
        string $context,
        AbstractModule $overrideModule,
        string|null $tmpDir = null, // ={appDir}/var/tmp/{context}
        string|null $logDir = null, // ={appDir}/var/log/{context}
    ): InjectorInterface {
        return PackageInjector::factory(self::newMeta($context, $tmpDir, $logDir), $context, $overrideModule);
    }

    /** @param non-empty-string $context */
    private static function newMeta(
        string $context,
        string|null $tmpDir,
        string|null $logDir,
    ): Meta {
        return new Meta(__NAMESPACE__, $context, dirname(__DIR__), $tmpDir, $logDir);
    }
}
