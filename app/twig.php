<?php
declare(strict_types=1);

$twigPaths = [
    \dirname(__DIR__) . '/common/templates',
    __DIR__ . '/' . ACTIVE_VHOST . '/templates',
];

$twigLoader = new \Twig_Loader_Filesystem($twigPaths);
$twig = new \Twig_Environment($twigLoader);
