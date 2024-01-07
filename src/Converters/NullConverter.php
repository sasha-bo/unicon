<?php

namespace Unicon\Unicon\Converters;

use SashaBo\Mapper\Value;
use Unicon\Unicon\ConversionResult;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\ConversionErrorType;

class NullConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, array $path): ?ConversionValue
    {
        return is_null($source) ? new ConversionValue(null) : null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ?ConversionValue
     */
    protected function convertGently(mixed $source, array $path): ?ConversionValue
    {
        if ($source === [] || $source === '' || $source === false || $source === 0) {
            return new ConversionValue(null);
        }

        return null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ?ConversionValue
     */
    protected function convertHumanly(mixed $source, array $path): ?ConversionValue
    {
        if (is_string($source) && strtolower($source) == 'null') {
            return new ConversionValue(null);
        }

        return null;
    }
}
