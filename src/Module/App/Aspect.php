<?php

namespace BEAR\Skeleton\Module\App;

use BEAR\Package;
use Ray\Di\AbstractModule;

class Aspect extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        /*
        $this->bindInterceptor(
             $this->matcher->any(),
             $this->matcher->annotatedWith('BEAR\Skeleton\Annotation\Bar'),
             [$this->requestInjection('BEAR\Skeleton\Interceptor\FooInterceptor')]
        );
        */
    }
}
