<?php

declare(strict_types=1);

namespace BEAR\Skeleton\Module;

use Koriym\EnvJson\EnvJson;
use BEAR\Package\AbstractAppModule;
use BEAR\Package\PackageModule;

use function dirname;

final class AppModule extends AbstractAppModule
{
    protected function configure(): void
    {
        (new EnvJson())->load(dirname(__DIR__, 2));
        $this->install(new PackageModule());
    }
}
