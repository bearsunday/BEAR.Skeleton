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
        $this->install(new DevObservationModule($this->appMeta->logDir . '/events'));
    }
}
