<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionValue;

class ObjectConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, string $type, array $path): ?ConversionValue
    {
        return is_object($source) ? new ConversionValue($source) : null;
    }
    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ?ConversionValue
     */
    protected function convertGently(mixed $source, string $type, array $path): ?ConversionValue
    {
        if (is_array($source)) {
            return new ConversionValue(StdClassConverter::fromArray($source));
        }

        return null;
    }
}
