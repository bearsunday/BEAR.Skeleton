<?php
require dirname(__DIR__) . '/autoload.php';
exit((require __DIR__ . '/bootstrap.php')(PHP_SAPI === 'cli' ? 'cli-hal-api-app' : 'hal-api-app'));
