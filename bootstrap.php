<?php
use BEAR\Package\Bootstrap;
use BEAR\Resource\ResourceObject;
use BEAR\Skeleton\Module\App;

return function (string $context, string $name = 'BEAR\Skeleton') : int {
    $app = (new Bootstrap)->getApp($name, $context, __DIR__);
    if ($app->httpCache->isNotModified($_SERVER)) {
        $app->httpCache->transfer();

        return 0;
    }
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

