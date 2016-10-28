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
    define('ACTIVE_VHOST', $vHosts['domains'][$host]);
} elseif (isset($vHosts['default_vhost'])) {
    define('ACTIVE_VHOST', $vHosts['default_vhost']);
} else {
    // Fatal error:
    BarkaneArts\fatalError('No virtual host configured', 400);
}

$hostConf = $vHosts['hosts'][ACTIVE_VHOST];

require_once BarkaneArts\getDataFile(
    __DIR__ . '/' . ACTIVE_VHOST . '/routes.php',
    __DIR__ . '/' . ACTIVE_VHOST . '/routes.tpl.php'
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

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/twig.php';

require_once BarkaneArts\getDataFile(
    __DIR__ . '/' . ACTIVE_VHOST . '/database.php',
    __DIR__ . '/' . ACTIVE_VHOST . '/database.tpl.php'
);

/**
 * Finally load any other boilerplate we need:
 */
if (\is_readable(__DIR__ . '/' . ACTIVE_VHOST . '/bootstrap.php')) {
    include_once __DIR__ . '/' . ACTIVE_VHOST . '/bootstrap.php';
}
