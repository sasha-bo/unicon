<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionValue;

class FloatConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, array $path): ?ConversionValue
    {
        return is_float($source) || is_int($source) ? new ConversionValue((float) $source) : null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ?ConversionValue
     */
    protected function convertGently(mixed $source, array $path): ?ConversionValue
    {
        if (is_string($source)) {
            if ($source == '') {
                return new ConversionValue(0.0);
            }
            if (preg_match('/^-?[0-9]+(\.[0-9]+)?$/', $source)) {
                return new ConversionValue((float) $source);
            }
            // in some languages coma is used instead of dot
            if (preg_match('/^-?[0-9]+,[0-9]+$/', $source)) {
                return new ConversionValue((float) str_replace(',', '.', $source));
            }
        }

        return null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ?ConversionValue
     */
    protected function convertForcibly(mixed $source, array $path): ?ConversionValue
    {
        return
            is_null($source) || is_scalar($source) || is_array($source)
                ? new ConversionValue((float) $source)
                : null;
    }
}
