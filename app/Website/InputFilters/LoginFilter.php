<?php
declare(strict_types=1);
namespace BarkaneArts\Website\InputFilters;

use BarkaneArts\Framework\Filter\StringFilter;
use BarkaneArts\Framework\FilterSet;

/**
 * Class LoginFilter
 * @package BarkaneArts\Website\InputFilters
 */
class LoginFilter extends FilterSet
{
    public function __construct()
    {
        $this
            ->addFilter(
                'username',
                (new StringFilter())
            )
            ->addFilter(
                'passphrase',
                (new StringFilter())
            )
        ;
    }
}
