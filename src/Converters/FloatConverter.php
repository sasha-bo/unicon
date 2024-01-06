<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionResult;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\ConversionErrorType;

class FloatConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, string $type, array $path): ?ConversionValue
    {
        return is_int($source) || is_float($source) ? new ConversionValue((float) $source) : null;
    }
}
