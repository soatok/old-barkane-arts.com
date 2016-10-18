<?php
declare(strict_types=1);

use \ParagonIE\Halite\Util;

$routes = [
    ['GET', '/', ['SimplePage', 'home']]
];

$routeCache = [
    'cacheFile' =>
        DATA_DIR . '/' . Util::hash(__DIR__) . '.cache'
];
