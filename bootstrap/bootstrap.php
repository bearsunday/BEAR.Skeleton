<?php declare(strict_types=1);
use BEAR\Package\Bootstrap;
use BEAR\Resource\ResourceObject;

return function (string $context) : int {
    $app = (new Bootstrap)->getApp('BEAR\Skeleton', $context, dirname(__DIR__));
    $request = $app->router->match($GLOBALS, $_SERVER);
    try {
        $response = $app->resource->{$request->method}->uri($request->path)($request->query);
        /* @var ResourceObject $response */
        $response->transfer($app->responder, $_SERVER);
        return 0;
    } catch (\Exception $e) {
        $app->error->handle($e, $request)->transfer();
        return 1;
    }
};
