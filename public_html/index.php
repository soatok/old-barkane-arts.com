<?php
declare(strict_types=1);

use FastRoute\Dispatcher;

require_once \dirname(__DIR__) . '/vendor/autoload.php';
require_once \dirname(__DIR__) . '/app/bootstrap.php';

/**
 * @global Dispatcher $dispatcher
 */

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '';

// Strip query string (?foo=bar) and decode URI
$pos = \strpos($uri, '?');
if ($pos !== false) {
    $uri = \substr($uri, 0, $pos);
}
$uri = \rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        BarkaneArts\fatalError('Route not found', 404);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        BarkaneArts\fatalError('Method not allowed', 408);
        break;
    case FastRoute\Dispatcher::FOUND:
        var_dump(\array_slice($routeInfo, 1));
        break;
}

