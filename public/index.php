<?php

use BEAR\Skeleton\Bootstrap;

require dirname(__DIR__) . '/autoload.php';
exit((new Bootstrap)(PHP_SAPI === 'cli-server' ? 'hal-app' : 'prod-hal-app', $GLOBALS, $_SERVER));
