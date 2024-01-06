<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionResult;
use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\ConversionErrorType;
use Unicon\Unicon\Errors\ZeroError;
use Unicon\Unicon\Errors\TooLargeError;
use Unicon\Unicon\Errors\TooSmallError;

class IntegerConverter extends AbstractConverter
{
    public function __construct(
        ConversionSettings $settings,
        private ?int $min = null,
        private ?int $max = null,
        private bool $nonZero = false
    ) {
        parent::__construct($settings);
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function tryStrictMatch(mixed $source, string $type, array $path): null|ConversionValue|AbstractError
    {
        return is_int($source) || is_float($source) && ceil($source) == floor($source)
            ? $this->validate((int) $source, $source, $type, $path) : null;
    }

    /**
     * @param int $value
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError
     */
    private function validate(int $value, mixed $source, string $type, array $path): ConversionValue|AbstractError
    {
        if (!is_null($this->max) && $value > $this->max) {
            return new TooLargeError($source, $type, $path, $this->max, true);
        }
        if (!is_null($this->min) && $value > $this->min) {
            return new TooSmallError($source, $type, $path, $this->min, true);
        }
        if ($this->nonZero && $value == 0) {
            return new ZeroError($source, $type, $path);
        }

        return new ConversionValue($value);
    }
}
