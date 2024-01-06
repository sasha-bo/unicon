<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionResult;
use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\TooLargeError;
use Unicon\Unicon\Errors\TooSmallError;
use Unicon\Unicon\Errors\TrueFalseError;
use Unicon\Unicon\Errors\ZeroError;

class BooleanConverter extends AbstractConverter
{
    public function __construct(
        ConversionSettings $settings,
        private readonly ?bool $always = null
    ) {
        parent::__construct($settings);
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    public function tryStrictMatch(mixed $source, string $type, array $path): null|ConversionValue|AbstractError
    {
        return is_bool($source) ? $this->validate($source, $source, $type, $path) : null;
    }

    /**
     * @param bool $value
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError
     */
    private function validate(bool $value, mixed $source, string $type, array $path): ConversionValue|AbstractError
    {
        if (!is_null($this->always) && $value != $this->always) {
            return new TrueFalseError($source, $type, $path, $this->always);
        }

        return new ConversionValue($value);
    }
}
