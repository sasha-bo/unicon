<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;

class StringConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, array $path): ?ConversionValue
    {
        return is_string($source) ? new ConversionValue($source) : null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertGently(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        return match (true) {
            is_null($source) => new ConversionValue(''),
            is_int($source), is_float($source) => new ConversionValue((string) $source),
            default => null
        };
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertHumanly(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        return match (true) {
            $source instanceof \DateTimeInterface => new ConversionValue($source->format($this->settings->getDateToStringFormat())),
            is_bool($source) => new ConversionValue($source ? 'true' : 'false'),
            default => null
        };
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertForcibly(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        return
            $source instanceof \Stringable
                ? new ConversionValue((string) $source)
                : null;
    }
}
