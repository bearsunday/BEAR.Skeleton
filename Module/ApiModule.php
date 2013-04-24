<?php

namespace Skeleton\Module;

use BEAR\Sunday\Module as SundayModule;
use BEAR\Package\Provide as PackageModule;

/**
 * API module
 */
class ApiModule extends ProdModule
{
    protected function configure()
    {
        $this->install(new ProdModule);

        // view module
        $this->install(new PackageModule\ResourceView\HalModule($this));
        //$this->install(new SundayModule\Resource\JsonModule($this));
    }
}
