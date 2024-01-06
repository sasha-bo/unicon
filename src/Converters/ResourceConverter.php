<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionResult;
use Unicon\Unicon\ConversionValue;

class ResourceConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, string $type, array $path): ?ConversionValue
    {
        return is_resource($source) ? new ConversionValue($source) : null;
    }
}
