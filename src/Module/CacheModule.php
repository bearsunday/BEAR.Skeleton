<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\Package\Provide\Cache\CacheDirProvider;
use BEAR\RepositoryModule\Annotation\ResourceObjectPool;
use Ray\PsrCacheModule\Annotation\CacheDir;
use Ray\PsrCacheModule\LocalCacheProvider;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Stores cacheable resources in a local store for development: prefix the app context with `cache-`
 *
 * APCu under a web server when the extension is loaded, otherwise files in var/tmp/{context}/cache.
 * Entries outlive code edits; restart the server or delete that directory to see an edited resource.
 */
final class CacheModule extends AbstractAppModule
{
    protected function configure(): void
    {
        $this->bind('')->annotatedWith(CacheDir::class)->toProvider(CacheDirProvider::class);
        $this->bind(AdapterInterface::class)->annotatedWith(ResourceObjectPool::class)->toProvider(LocalCacheProvider::class);
    }
}
