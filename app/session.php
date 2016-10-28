<?php
declare(strict_types=1);

use ParagonIE\Cookie\{
    Cookie,
    Session
};

Session::start(
    Cookie::SAME_SITE_RESTRICTION_STRICT,
    [
        'entropy_length' => '32',
        'cookie_httponly' => 'On'
    ]
);
