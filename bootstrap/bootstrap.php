<?php
use BEAR\Package\App;
use BEAR\Resource\ResourceObject;

require dirname(__DIR__) . '/autoload.php';

/* @global string $context */
$app = (new App)('BEAR\Skeleton', $context, dirname(__DIR__) . '.env');
$request = $app->router->match($GLOBALS, $_SERVER);
try {
    $response = $app->resource->{$request->method}->uri($request->path)($request->query);
    /* @var ResourceObject $response */
    $response->transfer($app->responder, $_SERVER);
    exit(0);
} catch (\Exception $e) {
    $app->error->handle($e, $request)->transfer();
    exit(1);
}
