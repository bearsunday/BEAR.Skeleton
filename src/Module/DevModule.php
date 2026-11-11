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
        // Cache log: one session per request (a CLI command or an HTTP request) in var/log/{context}/query-repository, the latest 10 kept,
        // read with `vendor/bin/stree var/log/dev-cli-hal-app/query-repository/latest.json`
        $this->install(new DevQueryRepositoryLogModule($this->appMeta->logDir . '/query-repository', keep: 10));
    }
}
