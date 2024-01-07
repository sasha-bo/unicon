<?php

namespace Unicon\Unicon\Converters\DateTime;

use Unicon\Unicon\ConversionValue;

class DateTimeInterfaceConverter extends DateTimeImmutableConverter
{
    public function tryStrictMatch(mixed $source, array $path): ?ConversionValue
    {
        return $source instanceof \DateTimeInterface ? new ConversionValue($source) : null;
    }
}
