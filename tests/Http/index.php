<?php

use BEAR\Skeleton\Bootstrap;

require dirname(__DIR__, 2) . '/vendor/autoload.php';
exit((new Bootstrap)('hal-app', $GLOBALS, $_SERVER));
