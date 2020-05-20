<?php
use BEAR\Skeleton\Injector;
use BEAR\Sunday\Extension\Application\AppInterface;

return function (string $context) : int {
    $app = (Injector::getInstance($context))->getInstance(AppInterface::class);
    if ($app->httpCache->isNotModified($_SERVER)) {
        $app->httpCache->transfer();

        return 0;
    }
    $request = $app->router->match($GLOBALS, $_SERVER);
    try {
        $response = $app->resource->{$request->method}->uri($request->path)($request->query);
        $response->transfer($app->responder, $_SERVER);

        return 0;
    } catch (Exception $e) {
        $app->error->handle($e, $request)->transfer();

        return 1;
    }
};
