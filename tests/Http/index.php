<?php
/**
 * HTTP test server script
 */
require dirname(__DIR__, 2) . '/vendor/autoload.php';
exit((require dirname(__DIR__, 2) . '/bootstrap.php')('prod-hal-app', $GLOBALS, $_SERVER));
