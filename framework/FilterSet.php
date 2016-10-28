<?php
declare(strict_types=1);
namespace BarkaneArts\Framework;

use DotNot\Exceptions\DotNotException;

/**
 * Class FilterSet
 * @package BarkaneArts\Framework
 */
class FilterSet
{
    /**
     * @var Filter[]
     */
    protected $filterMap = [];

    /**
     * Add a new filter to this input value
     *
     * @param string $path
     * @param Filter $filter
     * @return FilterSet
     */
    public function addFilter(
        string $path,
        Filter $filter
    ): self {
        if (!isset($this->filterMap[$path])) {
            $this->filterMap[$path] = [];
        }
        $this->filterMap[$path][] = $filter->setIndex($path);
        return $this;
    }

    /**
     * Filter a value.
     *
     * @param string $key
     * @param $multiDimensional
     * @return mixed
     */
    public function filterValue(string $key, $multiDimensional)
    {

        $pieces = \explode('.', $key);
        $filtered =& $multiDimensional;

        /**
         * @security This shouldn't be escapable. We know eval is evil, but
         *           there's not a more elegant way to process this in PHP.
         */
        if (\is_array($multiDimensional)) {
            $var = '$multiDimensional';
            foreach ($pieces as $piece) {
                $append = '[' . self::sanitize($piece) . ']';

                // Alphabetize the parent array
                eval(
                    'if (!isset(' . $var . $append . ')) {' . "\n" .
                    '    ' . $var . $append . ' = null;' . "\n" .
                    '}' . "\n" .
                    '\ksort(' . $var . ');' . "\n"
                );
                $var .= $append;
            }
            eval('$filtered =& ' . $var. ';');
        }

        // If we have filters, let's apply them:
        if (isset($this->filterMap[$key])) {
            foreach ($this->filterMap[$key] as $filter) {
                if ($filter instanceof Filter) {
                    $filtered = $filter->process($filtered);
                }
            }
        }

        return $multiDimensional;
    }

    /**
     * Only allow allow printable ASCII characters:
     *
     * @param string $input
     * @return string
     */
    protected static function sanitize(string $input): string
    {
        return \json_encode(
            \preg_replace('#[^\x20-\x7e]#', '', $input)
        );
    }

    /**
     * Process the input array.
     *
     * @param array $dataInput
     * @return array
     */
    public function __invoke(array $dataInput = []): array
    {
        foreach (\array_keys($this->filterMap) as $key) {
            $dataInput = $this->filterValue($key, $dataInput);
        }
        return $dataInput;
    }
}
