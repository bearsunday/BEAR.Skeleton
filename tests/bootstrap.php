<?php

use BEAR\Skeleton;

use Ray\Di\Injector;
use BEAR\Resource\ResourceInterface;
use BEAR\Skeleton\Module\AppModule;

error_reporting(E_ALL);

// load
$loader = require dirname(__DIR__) . '/vendor/autoload.php';
/** @var $loader \Composer\Autoload\ClassLoader */
$loader->addPsr4('BEAR\Skeleton\\', __DIR__);

// set the application path into the globals so we can access it in the tests.
$_ENV['TEST_DIR'] = __DIR__;

// clear cache files
require $_ENV['APP_DIR'] . '/bin/clear.php';

// set the resource client
$GLOBALS['RESOURCE'] = (new Injector(new AppModule, __DIR__ . '/tmp'))->getInstance(ResourceInterface::class);
