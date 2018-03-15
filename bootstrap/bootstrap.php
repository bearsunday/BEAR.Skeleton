<?php
use BEAR\Package\Bootstrap;

require dirname(__DIR__) . '/vendor/autoload.php';

/* @global string $context */
$app = (new Bootstrap)->getApp('BEAR\Skeleton', $context, dirname(__DIR__));
$request = $app->router->match($GLOBALS, $_SERVER);
try {
    $page = $app->resource->{$request->method}->uri($request->path)($request->query);
    /* @var $page \BEAR\Resource\ResourceObject */
    $page->transfer($app->responder, $_SERVER);
    exit(0);
} catch (\Exception $e) {
    $app->error->handle($e, $request)->transfer();
    exit(1);
}
