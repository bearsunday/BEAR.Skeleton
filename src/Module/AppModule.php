<?php

namespace BEAR\Skeleton\Module;

use BEAR\Package\PackageModule;
use Ray\Di\AbstractModule;
use josegonzalez\Dotenv\Loader as Dotenv;

class AppModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        Dotenv::load([
            'filepath' => dirname(dirname(__DIR__)) . '/.env',
            'toEnv' => true
        ]);
        $this->install(new PackageModule);
    }
}
