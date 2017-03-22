<?php

require __DIR__ . '/autoload.php';

use Ray\Di\Exception\Unbound;
use BEAR\Sunday\Extension\Application\AppInterface;
use BEAR\Package\AppInjector;

try {
    (new AppInjector('BEAR\Skeleton', 'prod-app'))->getInstance(AppInterface::class);
    exit(0);
} catch (Unbound $e) {
    echo $e;
    exit(1);
}
