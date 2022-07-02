<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\Package\PackageModule;

class AppModule extends AbstractAppModule
{
    protected function configure(): void
    {
        $this->install(new PackageModule());
    }
}
