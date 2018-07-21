<?php
namespace BEAR\Skeleton\Module;

use BEAR\Package\PackageModule;
use josegonzalez\Dotenv\Loader;
use Ray\Di\AbstractModule;

class AppModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $appDir = dirname(__DIR__, 2);
        (new Loader($appDir . '/.env'))->parse()->toEnv(true);
        $this->install(new PackageModule);
    }
}
