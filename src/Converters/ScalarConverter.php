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
        $this->stringConverter = new StringConverter($this->settings, 'string');
        parent::__construct(
            [
                new IntegerConverter($this->settings, 'int'),
                new FloatConverter($this->settings, 'float'),
                new BooleanConverter($this->settings, 'bool'),
                $this->stringConverter,
            ],
            $this->settings,
            'scalar'
        );
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, array $path): ?ConversionValue
    {
        return is_scalar($source) ? new ConversionValue($source) : null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertForcibly(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        return $this->stringConverter->convertForcibly($source, $path);
    }
}
