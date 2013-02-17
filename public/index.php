<?php
/**
 * A Web server script for use in production.
 *
 * This script is the entry point for an application whilst in production. This script is a base 
 * guideline and this procedural boot strap is gives you some defaults as a guide. 
 * You are free to change and configure this script at will.
 *
 * @package BEAR.Package
 * @global  $mode
 */
use BEAR\Resource\Exception\Parameter as BadRequest;
use BEAR\Resource\Exception\ResourceNotFound as NotFound;

/**
 * Here we get the production application instance. No $mode variable is needed as it defaults to Prod.
 *
 * @var $app \BEAR\Package\Provide\Application\AbstractApp
 */
$app = require dirname(__DIR__) . '/scripts/instance.php';

/**
 * Calling the match of a Bear.Sunday compatible router will give us the $method, $pagePath, $query to be used
 * in the page request.
 */
list($method, $pagePath, $query) = $app->router->match($GLOBALS);

/**
 * An attempt to request the page resource is made.
 * Upon failure the appropriate error code is assigned and forwarded to ERROR.
 */
try {
    $app->page = $app->resource->$method->uri('page://self/' . $pagePath)->withQuery($query)->eager->request();
} catch (NotFound $e) {
    $code = 404;
    goto ERROR;
} catch (BadRequest $e) {
    $code = 400;
    goto ERROR;
} catch (Exception $e) {
    $code = 503;
    error_log((string)$e);
    goto ERROR;
}

/**
 * OK: Sets the response resources and renders
 * ERROR: sets the response code and loads error page.
 */ 
OK: {
    error_reporting(E_ALL);
    $app->response->setResource($app->page)->render()->send();
    exit(0);
}

ERROR: {
    /** @noinspection PhpUnreachableStatementInspection */
    http_response_code($code);
    require dirname(__DIR__) . "/http/{$code}.php";
    exit(1);
}
