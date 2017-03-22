<?php

require __DIR__ . '/autoload.php';

use BEAR\Package\AppInjector;
use BEAR\Sunday\Extension\Application\AppInterface;
use Ray\Di\Exception\Unbound;

try {
    (new AppInjector('BEAR\Skeleton', 'prod-app'))->getInstance(AppInterface::class);
    exit(0);
} catch (Unbound $e) {
    echo $e;
    exit(1);
}
