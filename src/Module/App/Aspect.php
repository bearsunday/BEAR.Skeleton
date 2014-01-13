<?php

namespace BEAR\Skeleton\Module\App;

use BEAR\Package;
use Ray\Di\AbstractModule;

/**
 * Application Aspect
 */
class Aspect extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        /*
        $fooInterceptor = $this->requestInjection('BEAR\Skeleton\Interceptor\FooInterceptor');
        $this->bindInterceptor(
             $this->matcher->any(),
             $this->matcher->annotatedWith('BEAR\Skeleton\Annotation\Bar'),
                 [$fooInterceptor]
        );
        */
    }
}
