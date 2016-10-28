<?php
declare(strict_types=1);

use ParagonIE\AntiCSRF\AntiCSRF;

$twigPaths = [
    \dirname(__DIR__) . '/common/templates',
    __DIR__ . '/' . ACTIVE_VHOST . '/templates',
];

$twigLoader = new \Twig_Loader_Filesystem($twigPaths);
$twig = new \Twig_Environment(
    $twigLoader,
    [
        // Automatically apply |e('html') to template variables. Use |raw if you need it.s
        'autoescape' => true
    ]
);

$twig->addFunction(
    new Twig_SimpleFunction(
        'csrf_token',
        function(string $path = '') {
            static $CSRF = null;
            if (!$CSRF) {
                $CSRF = new AntiCSRF();
            }
            return $CSRF->insertToken($path, true);
        },
        ['is_safe' => ['html']]
    )
);
