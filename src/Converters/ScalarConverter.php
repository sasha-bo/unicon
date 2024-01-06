<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;

class ScalarConverter extends UnionConverter
{
    private StringConverter $stringConverter;
    public function __construct(
        protected readonly ConversionSettings $settings
    ) {
        $this->stringConverter = new StringConverter($this->settings);
        parent::__construct(
            [
                new IntegerConverter($this->settings),
                new FloatConverter($this->settings),
                new BooleanConverter($this->settings),
                $this->stringConverter,
            ],
            $this->settings
        );
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, string $type, array $path): ?ConversionValue
    {
        return is_scalar($source) ? new ConversionValue($source) : null;
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertForcibly(mixed $source, string $type, array $path): null|ConversionValue|AbstractError
    {
        return $this->stringConverter->convert($source, $type, $path);
    }
}
