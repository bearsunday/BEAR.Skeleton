#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * CLI compile entry.
 *
 * Usage: php bin/compile.php [context]
 *
 * @see https://bearsunday.github.io/manuals/1.0/en/production.html#compilation
 */

use BEAR\Package\Compiler;
use BEAR\Skeleton\Injector;

require dirname(__DIR__) . '/vendor/autoload.php';

// Load build-time-only stubs (null objects / fake env) if present.
$dotCompile = dirname(__DIR__) . '/.compile.php';
is_file($dotCompile) && require $dotCompile;

$context = $argv[1] ?? 'prod-app';

exit(Compiler::fromInjector(Injector::getInstance($context), $context)());
