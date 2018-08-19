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
        $env = $appDir . '/.env';
        if (file_exists($env)) {
            (new Loader($env))->parse()->toEnv(true);
        }
        $this->install(new PackageModule);
    }
}
