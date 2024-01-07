<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionResult;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;

class CallableConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, array $path): ?ConversionValue
    {
        return is_callable($source) ? new ConversionValue($source) : null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue
     */
    protected function convertForcibly(mixed $source, array $path): ConversionValue
    {
        return new ConversionValue(
            function () use ($source) {
                return $source;
            }
        );
    }
}
