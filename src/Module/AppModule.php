<?php

namespace BEAR\Skeleton\Module;

use BEAR\Package\AppMeta;
use BEAR\Package\PackageModule;
use Ray\Di\AbstractModule;
use BEAR\Sunday\Extension\Application\AppInterface;

class AppModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new PackageModule(new AppMeta('BEAR\Skeleton')));
    }
}
