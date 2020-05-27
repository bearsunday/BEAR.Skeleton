<?php
namespace BEAR\Skeleton;

use BEAR\Skeleton\Module\App;
use BEAR\Sunday\Extension\Application\AppInterface;
use BEAR\Sunday\Extension\Router\NullMatch;

return
    /**
     * @param array{_GET: array<string, string|array>, _POST: array<string, string|array>} $globals $GLOBALS
     * @param array<string, mixed>                                                         $server  $_SERVER
     */
    function (string $context, array $globals, array $server) : int {
        $app = (Injector::getInstance($context))->getInstance(AppInterface::class);
        assert($app instanceof App);
        if ($app->httpCache->isNotModified($server)) {
            $app->httpCache->transfer();

            return 0;
        }
        try {
            $request = $app->router->match($globals, $server);
            $response = $app->resource->{$request->method}->uri($request->path)($request->query);
            $response->transfer($app->responder, $server);

            return 0;
        } catch (\Exception $e) {
            $app->error->handle($e, $request ?? new NullMatch)->transfer();

            return 1;
        }
    };
