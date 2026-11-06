<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\QueryRepository\DevQueryRepositoryLogModule;

/**
 * Development context: prefix the app context with `dev-` (`dev-hal-app`, `dev-cli-hal-app`)
 */
final class DevModule extends AbstractAppModule
{
    protected function configure(): void
    {
        // Cache log: one session per request in var/log/{context}/query-repository,
        // read with `vendor/bin/stree var/log/dev-hal-app/query-repository/latest.json`
        $this->install(new DevQueryRepositoryLogModule($this->appMeta->logDir . '/query-repository'));
    }
}
