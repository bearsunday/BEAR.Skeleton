<?php

// dev tools
$loader = require dirname(__DIR__) . '/vendor/autoload.php';
/** @var $loader \Composer\Autoload\ClassLoader */
$loader->add('', [__DIR__]);
ini_set('error_log', sys_get_temp_dir() . 'app-test.log');


$GLOBALS['app'] = require dirname(__DIR__) . '/scripts/instance.php';

// application root
chdir(dirname(__DIR__));
