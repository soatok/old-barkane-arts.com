<?php
declare(strict_types=1);

use \ParagonIE\Halite\Util;

$routes = [
    [
        'GET',
        '/',
        ['SimplePage', 'home']
    ],
    [
        'GET',
        '/dashboard',
        ['Dashboard', 'index']
    ],
    [
        ['GET', 'POST'],
        '/login',
        ['Gateway', 'login']
    ],
    [
        'GET',
        '/logout',
        ['Gateway', 'logout']
    ],
    [
        ['GET', 'POST'],
        '/register',
        ['Gateway', 'register']
    ],
];

$routeCache = [
    'cacheFile' =>
        DATA_DIR . '/' . Util::hash(__DIR__) . '.cache'
];
