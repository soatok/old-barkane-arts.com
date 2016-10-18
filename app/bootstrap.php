<?php
declare(strict_types=1);

require_once __DIR__ . '/utility.php';

define('DATA_DIR', \dirname(__DIR__) . '/data/');

require_once BarkaneArts\getDataFile(
    DATA_DIR . '/vhosts.php',
    DATA_DIR . '/vhosts.tpl.php'
);

/**
 * @global array $vHosts
 */

$host = \strtolower($_SERVER['HTTP_HOST']);

if (isset($vHosts['domains'][$host])) {
    $dir = __DIR__ . '/' . $vHosts['domains'][$host];
} elseif (isset($vHosts['default_vhost'])) {
    $dir = __DIR__ . '/' . $vHosts['default_vhost'];
} else {
    BarkaneArts\fatalError('No virtual host configured', 400);
}

require_once BarkaneArts\getDataFile(
    $dir . '/routes.php',
    $dir . '/routes.tpl.php'
);
/**
 * Let's setup our dispatcher.
 *
 * @global array $routes
 * @global array $routeCache
 */

if ($routeCache) {
    $dispatcher = FastRoute\cachedDispatcher(
        function (FastRoute\RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $r->addRoute(...$route);
            }
        },
        $routeCache
    );
} else {
    $dispatcher = FastRoute\simpleDispatcher(
        function (FastRoute\RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $r->addRoute(...$route);
            }
        }
    );
}