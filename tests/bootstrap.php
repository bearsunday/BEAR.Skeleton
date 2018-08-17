<?php
use josegonzalez\Dotenv\Loader;

require dirname(__DIR__) . '/vendor/autoload.php';
(new Loader(dirname(__DIR__) . '/.env'))->parse()->toEnv(true);
