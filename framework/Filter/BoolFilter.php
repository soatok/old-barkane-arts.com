<?php
declare(strict_types=1);
namespace BarkaneArts\Framework\Filter;

use BarkaneArts\Framework\Filter;

/**
 * Class BoolFilter
 * @package BarkaneArts\Framework\Filter
 */
class BoolFilter extends Filter
{
    /**
     * @var mixed
     */
    protected $default = false;

    /**
     * @var string
     */
    protected $type = 'bool';

    /**
     * Sets the expected input type (e.g. string, boolean)
     *
     * @param string $typeIndicator
     * @return Filter
     * @throws \Exception
     */
    public function setType(string $typeIndicator): Filter
    {
        if ($typeIndicator !== 'bool') {
            throw new \Exception(
                'Type must always be set to "bool".'
            );
        }
        return parent::setType('bool');
    }

    /**
     * Set the default value (not applicable to booleans)
     *
     * @param mixed $value
     * @return Filter
     */
    public function setDefault($value): Filter
    {
        return parent::setDefault($value);
    }
}
