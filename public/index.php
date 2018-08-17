<?php
(require dirname(__DIR__) . '/bootstrap/bootstrap.php')(PHP_SAPI === 'cli-server' ? 'hal-app' : 'prod-hal-app');
