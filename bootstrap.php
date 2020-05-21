<?php
namespace BEAR\Skeleton;

use BEAR\Skeleton\Module\App;
use BEAR\Sunday\Extension\Application\AppInterface;

return function (string $context) : int {
    $app = (Injector::getInstance($context))->getInstance(AppInterface::class);
    assert($app instanceof App);
    try {
        if ($app->httpCache->isNotModified($_SERVER)) {
            $app->httpCache->transfer();

            return 0;
        }
        $request = $app->router->match($GLOBALS, $_SERVER);
        $response = $app->resource->{$request->method}->uri($request->path)($request->query);
        $response->transfer($app->responder, $_SERVER);

        return 0;
    } catch (\Exception $e) {
        $app->error->handle($e, $request)->transfer();

        return 1;
    }
};
