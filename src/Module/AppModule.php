<?php

namespace BEAR\Skeleton\Module;

use BEAR\Package\AppMeta;
use BEAR\Package\PackageModule;
use BEAR\Sunday\Extension\Application\AppInterface;
use Ray\Di\AbstractModule;

class AppModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(AppInterface::class)->to(App::class);

        $this->install(new PackageModule(new AppMeta('BEAR\Skeleton')));
    }
}
