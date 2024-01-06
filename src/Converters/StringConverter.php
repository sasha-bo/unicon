<?php

namespace Unicon\Unicon\Converters;

use SashaBo\Mapper\Value;
use Unicon\Unicon\ConversionResult;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;

class StringConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, string $type, array $path): ?ConversionValue
    {
        return is_string($source) ? new ConversionValue($source) : null;
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertGently(mixed $source, string $type, array $path): null|ConversionValue|AbstractError
    {
        return
            is_null($source) || is_scalar($source)
                ? new ConversionValue((string) $source)
                : null;
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertHumanly(mixed $source, string $type, array $path): null|ConversionValue|AbstractError
    {
        if ($source instanceof \DateTimeInterface) {
            return new ConversionValue($source->format($this->settings->getDateToStringFormat()));
        }
        return null;
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertForcibly(mixed $source, string $type, array $path): null|ConversionValue|AbstractError
    {
        if ($source instanceof \DateTimeInterface) {
            return new ConversionValue($source->format($this->settings->getDateToStringFormat()));
        }
        return
            $source instanceof \Stringable
                ? new ConversionValue((string) $source)
                : null;
    }
}
