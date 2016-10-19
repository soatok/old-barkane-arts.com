<?php
declare(strict_types=1);

use ParagonIE\EasyDB\Factory;

// OVerwrite me in production!
$database = Factory::create(
    // For example...
    'sqlite::memory:'
);
