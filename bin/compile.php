#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * CLI compile entry.
 *
 * Usage: php bin/compile.php [context]
 */

use BEAR\Package\Compiler;
use BEAR\Skeleton\Injector;

require dirname(__DIR__) . '/vendor/autoload.php';

$context = $argv[1] ?? 'prod-app';
$tmpDir = null; // ={appDir}/var/tmp/{context}
$logDir = null; // ={appDir}/var/log/{context}

exit(
    Compiler::fromInjector(
        Injector::getInstance($context, $tmpDir, $logDir),
        $context,
    )->run()
);
