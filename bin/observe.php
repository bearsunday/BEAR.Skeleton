<?php

declare(strict_types=1);

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\Skeleton\Injector;
use BEAR\Skeleton\Log\LogFileWriter;
use BEAR\Sunday\Extension\Application\AppInterface;

require dirname(__DIR__) . '/autoload.php';

$method = $argv[1] ?? null;
$uri = $argv[2] ?? null;
if ($method === null || $uri === null) {
    fwrite(STDERR, "Usage: php bin/observe.php <method> <uri> [key=value...]\n");
    exit(1);
}

$params = [];
foreach (array_slice($argv, 3) as $pair) {
    [$key, $value] = explode('=', $pair, 2) + [1 => ''];
    $params[$key] = $value;
}

// Fixed, not read from an environment variable: bin/page.php and bin/app.php carry their
// contexts the same way, and this is the dev-context counterpart to both.
$injector = Injector::getInstance('dev-cli-hal-app');
$app = $injector->getInstance(AppInterface::class);

$ro = $app->resource->{$method}->uri($uri)($params);
echo $ro->code, "\n", $ro, "\n";

// DevModule arms the write at process shutdown (see src/Module/DevModule.php); this script
// never calls flush() itself, so a second request made the same way does not race it.
$logDir = $injector->getInstance(AbstractAppMeta::class)->logDir;
echo 'Log: ' . $logDir . '/' . LogFileWriter::LATEST . "\n";
echo 'View:  vendor/bin/stree ' . $logDir . '/' . LogFileWriter::LATEST . "\n";
