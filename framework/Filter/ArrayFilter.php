<?php
declare(strict_types=1);
namespace BarkaneArts\Framework\Filter;

use BarkaneArts\Framework\Filter;

/**
 * Class ArrayFilter
 * @package BarkaneArts\Framework\Filter
 */
class ArrayFilter extends Filter
{
    /**
     * @var mixed
     */
    protected $default = [];

    /**
     * @var string
     */
    protected $type = 'array';
}
