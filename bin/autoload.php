<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$laoder = require dirname(__DIR__) . '/vendor/autoload.php';
/* @var $loader \Composer\Autoload\ClassLoader */
AnnotationRegistry::registerLoader([$laoder, 'loadClass']);
