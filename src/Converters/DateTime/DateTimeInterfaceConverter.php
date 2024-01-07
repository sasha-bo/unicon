<?php

namespace Unicon\Unicon\Converters\DateTime;

use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Converters\AbstractConverter;

class DateTimeInterfaceConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, array $path): ?ConversionValue
    {
        return $source instanceof \DateTimeInterface ? new ConversionValue($source) : null;
    }
}
