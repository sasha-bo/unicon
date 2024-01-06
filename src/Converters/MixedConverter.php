<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionResult;
use Unicon\Unicon\ConversionValue;

class MixedConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue
     */
    public function tryStrictMatch(mixed $source, string $type, array $path): ConversionValue
    {
        return new ConversionValue($source);
    }
}
