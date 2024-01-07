<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionValue;

class IterableConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, array $path): ?ConversionValue
    {
        return is_iterable($source) ? new ConversionValue($source) : null;
    }
}
