<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionValue;

class MixedConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue
     */
    public function tryStrictMatch(mixed $source, array $path): ConversionValue
    {
        return new ConversionValue($source);
    }
}
