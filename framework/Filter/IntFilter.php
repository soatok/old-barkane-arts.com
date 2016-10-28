<?php
declare(strict_types=1);
namespace BarkaneArts\Framework\Filter;

use BarkaneArts\Framework\Filter;

/**
 * Class IntFilter
 * @package BarkaneArts\Framework\Filter
 */
class IntFilter extends Filter
{
    /**
     * @var mixed
     */
    protected $default = 0;

    /**
     * @var string
     */
    protected $type = 'int';
}
