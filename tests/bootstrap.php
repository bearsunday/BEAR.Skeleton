<?php

use BEAR\Skeleton;
use BEAR\Skeleton\Module\AppModule;
use BEAR\AppMeta\AppMeta;
use BEAR\Package\AppMetaModule;
use BEAR\Resource\ResourceInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Ray\Di\Injector;

error_reporting(E_ALL);

// load
$loader = require dirname(__DIR__) . '/vendor/autoload.php';
/** @var $loader \Composer\Autoload\ClassLoader */
$loader->addPsr4('BEAR\Skeleton\\', __DIR__);
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

// set the application path into the globals so we can access it in the tests.
$_ENV['TEST_DIR'] = __DIR__;
$_ENV['TMP_DIR'] = __DIR__ . '/tmp';

// set the resource client
// set the resource client
$module = new AppModule(new AppMetaModule(new AppMeta('BEAR\Skeleton')));
$GLOBALS['RESOURCE'] = (new Injector($module, __DIR__ . $_ENV['TMP_DIR']))->getInstance(ResourceInterface::class);
