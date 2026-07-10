#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * CLI compile entry.
 *
 * Usage: php bin/compile.php [context]
 * Optional env (process edge only): BEAR_TMP_DIR, BEAR_LOG_DIR
 */

use BEAR\Package\Compiler;
use BEAR\Skeleton\Injector;

require dirname(__DIR__) . '/vendor/autoload.php';

$context = $argv[1] ?? 'prod-app';
$tmpDir = getenv('BEAR_TMP_DIR') ?: null;
$logDir = getenv('BEAR_LOG_DIR') ?: null;

exit(
    Compiler::fromInjector(
        Injector::getInstance($context, $tmpDir, $logDir),
        $context,
    )->run()
);
