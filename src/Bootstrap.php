<?php

declare(strict_types=1);

namespace BEAR\Skeleton;

use BEAR\Resource\ResourceObject;
use BEAR\Skeleton\Module\App;
use BEAR\Sunday\Extension\Application\AppInterface;
use BEAR\Sunday\Extension\Router\NullMatch;
use BEAR\Sunday\Extension\Router\RouterInterface;
use Throwable;

use function assert;

/**
 * @psalm-import-type Globals from RouterInterface
 * @psalm-import-type Server from RouterInterface
 */
final class Bootstrap
{
    /**
     * @param Globals               $globals
     * @param Server                $server
     * @param non-empty-string|null $writeDir absolute base to write under, when this directory is read-only
     *
     * @return 0|1
     */
    public function __invoke(string $context, array $globals, array $server, string|null $writeDir = null): int
    {
        $app = Injector::getInstance($context, $writeDir)->getInstance(AppInterface::class);
        assert($app instanceof App);
        if ($app->httpCache->isNotModified($server)) {
            $app->httpCache->transfer();

            return 0;
        }

        $request = new NullMatch();
        try {
            $request = $app->router->match($globals, $server);
            $response = $app->resource->{$request->method}->uri($request->path)($request->query);
            assert($response instanceof ResourceObject);
            $response->transfer($app->responder, $server);

            return 0;
        } catch (Throwable $e) {
            $app->throwableHandler->handle($e, $request)->transfer();

            return 1;
        }
    }
}
