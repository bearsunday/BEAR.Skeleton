<?php

namespace BEAR\Skeleton\Module\App;

use BEAR\Package;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

/**
 * Application Dependency
 */
class Dependency extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
         // $this->bind('BEAR\Skeleton\FooInterface')->to('BEAR\Skeleton\Foo');
    }
}
