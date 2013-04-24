<?php

namespace Skeleton\Module;

use BEAR\Package\Module as PackageModule;
use Ray\Di\AbstractModule;

/**
 * Dev module
 */
class DevModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new App\AppModule('dev'));
        $this->install(new PackageModule\Resource\DevResourceModule($this));
    }
}
