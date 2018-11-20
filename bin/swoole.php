<?php

// @See http://bearsunday.github.io/manuals/1.0/en/swoole.html
require dirname(__DIR__) . '/autoload.php';
exit((require dirname(__DIR__) . '/vendor/bear/swoole/bootstrap.php')(
    'prod-app',       // context
    'BEAR\Skeleton',      // application name
    '127.0.0.1',          // IP
    '8080'                // port
));
