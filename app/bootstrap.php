<?php
declare(strict_types=1);

require_once __DIR__ . '/utility.php';

define('DATA_DIR', \dirname(__DIR__) . '/data/');

require_once BarkaneArts\getDataFile(
    DATA_DIR . 'vhosts.php',
    DATA_DIR . 'vhosts.tpl.php'
);

/**
 * @global array $vHosts
 */

// Use a lowercase version of the current HTTP Host header to determine
// which application to load:
$host = \strtolower($_SERVER['HTTP_HOST']);

// Let's load the routes.php file:
if (isset($vHosts['domains'][$host])) {
    $dir = __DIR__ . '/' . $vHosts['domains'][$host];
    $hostConf = $vHosts['hosts'][$vHosts['domains'][$host]];
} elseif (isset($vHosts['default_vhost'])) {
    $dir = __DIR__ . '/' . $vHosts['default_vhost'];
    $hostConf = $vHosts['hosts'][$vHosts['default_vhost']];
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
    // If $routeCache was defined, we're caching our routes instead of
    // building them on each pageload:
    $dispatcher = FastRoute\cachedDispatcher(
        function (FastRoute\RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $r->addRoute(...$route);
            }
        },
        $routeCache
    );
} else {
    // Don't cache, reassemble at runtime:
    $dispatcher = FastRoute\simpleDispatcher(
        function (FastRoute\RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $r->addRoute(...$route);
            }
        }
    );
}

if (\is_readable($dir . '/bootstrap.php')) {
    include_once $dir . '/bootstrap.php';
}

/**
 * Finally load any other boilerplate we need:
 */
if (\is_readable($dir . '/bootstrap.php')) {
    include_once $dir . '/bootstrap.php';
}
