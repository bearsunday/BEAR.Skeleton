<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\QueryRepository\DevQueryRepositoryLogModule;
use BEAR\RepositoryModule\Annotation\ResourceObjectPool;
use Ray\Di\Scope;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Development context that records the cache log: prefix the app context with `dev-`
 * (`dev-app`, `dev-hal-app`, `dev-cli-hal-app`)
 *
 * Each request writes one session to var/log/{context}/query-repository, and
 * `vendor/bin/stree var/log/dev-hal-app/query-repository/latest.json` renders the last one.
 */
final class DevModule extends AbstractAppModule
{
    protected function configure(): void
    {
        $this->install(new DevQueryRepositoryLogModule($this->appMeta->logDir . '/query-repository'));
        // The default NullAdapter misses every read, so a hit or a 304 would never be logged
        $this->bind(AdapterInterface::class)->annotatedWith(ResourceObjectPool::class)
            ->toProvider(DevPoolProvider::class)->in(Scope::SINGLETON);
    }
}
