<?php

use BEAR\Package\Dev\Dev;
use Ray\Di\Injector;
use BEAR\Skeleton\Module\AppModule;

error_reporting(E_ALL);

// load
$loader = require_once dirname(__DIR__) . '/bootstrap/autoload.php';

// enable debug print p($var);
(new Dev())->loadDevFunctions();

// set the application path into the globals so we can access it in the tests.
$GLOBALS['APP_DIR'] = dirname(__DIR__);

// set the resource client
$GLOBALS['RESOURCE'] = Injector::create([new AppModule('test')])->getInstance('\BEAR\Resource\ResourceInterface');
