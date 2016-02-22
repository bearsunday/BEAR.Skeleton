<?php

namespace BEAR\Skeleton\Module;

use josegonzalez\Dotenv\Loader as Dotenv;
use Koriym\DbAppPackage\DbAppPackage;
use Ray\Di\AbstractModule;

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
        $this->install(new DbAppPackage($_ENV['DB_DSN'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_READ']));
    }
}
