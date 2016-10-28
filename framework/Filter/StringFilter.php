<?php
declare(strict_types=1);
namespace BarkaneArts\Framework\Filter;

use ParagonIE\ConstantTime\Binary;
use BarkaneArts\Framework\Filter;

/**
 * Class StringFilter
 * @package BarkaneArts\Framework\Filter
 */
class StringFilter extends Filter
{
    /**
     * @var mixed
     */
    protected $default = '';

    /**
     * @var string
     */
    protected $pattern = '';

    /**
     * @var string
     */
    protected $type = 'string';

    /**
     * @param string $input
     * @return string
     * @throws \TypeError
     */
    public static function nonEmpty(string $input): string
    {
        if (Binary::safeStrlen($input) < 1) {
            throw new \TypeError();
        }
        return $input;
    }

    /**
     * Set a regular expression pattern that the input string
     * must match.
     *
     * @param string $pattern
     * @return self
     */
    public function setPattern(string $pattern = ''): self
    {
        if (empty($pattern)) {
            $this->pattern = '';
        } else {
            $this->pattern = '#' . \preg_quote($pattern, '#') . '#';
        }
        return $this;
    }

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
