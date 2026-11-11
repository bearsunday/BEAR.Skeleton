<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Module;

use BEAR\EventSourcing\Module\DevObservationModule;
use BEAR\Package\AbstractAppModule;

/**
 * Development context: prefix the app context with `dev-` (`dev-hal-app`, `dev-cli-hal-app`)
 */
final class DevModule extends AbstractAppModule
{
    protected function configure(): void
    {
        // Each request (a CLI command or an HTTP request) as one tree of the resource requests it made and their cache
        // decisions, in var/log/{context}/events with response bodies under bodies/, the latest 10 kept;
        // read with `vendor/bin/stree var/log/dev-cli-hal-app/events/latest.json`
        $this->install(new DevObservationModule($this->appMeta->logDir . '/events', keep: 10));
    }
}
