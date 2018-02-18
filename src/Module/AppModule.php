<?php
namespace BEAR\Skeleton\Module;

use BEAR\Package\PackageModule;
use josegonzalez\Dotenv\Loader as Dotenv;
use Ray\Di\AbstractModule;

class AppModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $appDir = dirname(__DIR__, 2);
        Dotenv::load([
            'filepath' => $appDir . '/.env',
            'toEnv' => true
        ]);
        $this->install(new PackageModule);
    }
}
