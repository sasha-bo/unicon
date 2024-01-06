<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionValue;

class StdClassConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, string $type, array $path): ?ConversionValue
    {
        return $source instanceof \stdClass ? new ConversionValue($source) : null;
    }
    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ?ConversionValue
     */
    protected function convertGently(mixed $source, string $type, array $path): ?ConversionValue
    {
        if (is_array($source)) {
            return new ConversionValue(self::fromArray($source));
        }

        return null;
    }

    /**
     * @param array<mixed> $source
     * @return \stdClass
     */
    public static function fromArray(array $source): \stdClass
    {
        $object = new \stdClass();
        foreach ($source as $key => $value) {
            $object->$key = $value;
        }

        return $object;
    }
}
