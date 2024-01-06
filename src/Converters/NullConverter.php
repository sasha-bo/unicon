<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionResult;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\ConversionErrorType;

class NullConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, string $type, array $path): ?ConversionValue
    {
        return is_null($source) ? new ConversionValue(null) : null;
    }
}
