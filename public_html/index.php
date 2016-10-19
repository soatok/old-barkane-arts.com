<?php
declare(strict_types=1);

use FastRoute\Dispatcher;
use ParagonIE\EasyDB\EasyDB;

require_once \dirname(__DIR__) . '/vendor/autoload.php';
require_once \dirname(__DIR__) . '/app/bootstrap.php';

/**
 * @global EasyDB $database
 * @global Dispatcher $dispatcher
 * @global array $hostConf
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
        list ($route, $args) = \array_values(
            \array_slice($routeInfo, 1, 2)
        );
        if (\strpos($route[0], '\\') === false) {
            $class = $hostConf['namespace'] . '\\Controller\\' . $route[0];
        } else {
            $class = $route[0];
        }
        $controller = new $class($twig, $database);
        try {
            $method = $route[1];
            $controller->{$method}(...$args);
        } catch (\Throwable $e) {
            BarkaneArts\fatalError('Internal Server Error', 500);
        }
        break;
}

