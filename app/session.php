<?php
declare(strict_types=1);

use ParagonIE\Cookie\{
    Cookie,
    Session
};

\ini_set('session.entropy_length', 32);
\ini_set('session.cookie_httponly', true);

Session::start(Cookie::SAME_SITE_RESTRICTION_STRICT);
