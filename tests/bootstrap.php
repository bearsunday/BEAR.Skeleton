<?php

use BEAR\Skeleton;
use BEAR\Resource\Module\ResourceModule;
use BEAR\Resource\ResourceInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Ray\Di\Injector;

error_reporting(E_ALL);

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
/* @var $loader \Composer\Autoload\ClassLoader */
$loader->addPsr4('BEAR\Skeleton\\', __DIR__);
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

// set the application path into the globals so we can access it in the tests.
$_ENV['TEST_DIR'] = __DIR__;
$_ENV['TMP_DIR'] = __DIR__ . '/tmp';
// set the resource client
$GLOBALS['RESOURCE'] = (new Injector(new ResourceModule('MyVendor\Kuma'), $_ENV['TMP_DIR']))->getInstance(ResourceInterface::class);
