<?php
declare(strict_types=1);
namespace BarkaneArts\Website\InputFilters;

use BarkaneArts\Framework\Filter\StringFilter;
use BarkaneArts\Framework\FilterSet;

/**
 * Class RegisterFilter
 * @package BarkaneArts\Website\InputFilters
 */
class RegisterFilter extends FilterSet
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
