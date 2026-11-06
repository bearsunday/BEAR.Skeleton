<?php

declare(strict_types=1);

use BEAR\Skeleton\Bootstrap;

require dirname(__DIR__) . '/autoload.php';
$context = PHP_SAPI === 'cli-server' ? (getenv('BEAR_CONTEXT') ?: 'hal-app') : 'prod-hal-app';
exit((new Bootstrap())($context, $GLOBALS, $_SERVER));
