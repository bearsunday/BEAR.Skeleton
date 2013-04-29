<?php

// dev tools
require_once dirname(__DIR__) . '/scripts/bootstrap.php';

$GLOBALS['app'] = require dirname(__DIR__) . '/scripts/instance.php';

// application root
chdir(dirname(__DIR__));
