<?php
declare(strict_types=1);

namespace BarkaneArts\Website\Controller;
use \BarkaneArts\Framework\Controller;

/**
 * Class SimplePage
 */
class SimplePage extends Controller
{
    /**
     * @param string $method
     * @param array ...$args
     */
    public function __call(string $method, array $args = [])
    {
        $this->view('simple/' . $method, $args);
    }
}
