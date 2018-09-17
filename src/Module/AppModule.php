<?php
namespace BEAR\Skeleton\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\Package\PackageModule;
use josegonzalez\Dotenv\Loader;

class AppModule extends AbstractAppModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $appDir = $this->appMeta->appDir;
        $this->loadEnv($appDir . '/.env');
        $this->install(new PackageModule);
    }

    private function loadEnv(string $env)
    {
        if (file_exists($env)) {
            (new Loader($env))->parse()->putenv();
        }
    }
}
