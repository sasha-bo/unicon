<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\TooLargeError;
use Unicon\Unicon\Errors\TooSmallError;
use Unicon\Unicon\Errors\ZeroError;

class IntegerConverter extends AbstractConverter
{
    public function __construct(
        ConversionSettings $settings,
        string $type,
        private ?int $min = null,
        private ?int $max = null,
        private bool $nonZero = false
    ) {
        parent::__construct($settings, $type);
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function tryStrictMatch(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        return is_int($source) || is_float($source) && ceil($source) == floor($source)
            ? $this->validate((int) $source, $source, $path) : null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertGently(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        if (is_null($source)) {
            return $this->validate(0, $source, $path);
        }
        if (is_string($source)) {
            if ($source == '') {
                return $this->validate(0, $source, $path);
            }
            if (preg_match('/^-?[0-9]+$/', $source)) {
                return $this->validate((int) $source, $source, $path);
            }
        }

        return null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertForcibly(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        return
            is_null($source) || is_scalar($source) || is_array($source)
                ? $this->validate((int) $source, $source, $path)
                : null;
    }

    /**
     * @param int $value
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError
     */
    private function validate(int $value, mixed $source, array $path): ConversionValue|AbstractError
    {
        if (!is_null($this->max) && $value > $this->max) {
            return new TooLargeError($source, $this->type, $path, $this->max, true);
        }
        if (!is_null($this->min) && $value < $this->min) {
            return new TooSmallError($source, $this->type, $path, $this->min, true);
        }
        if ($this->nonZero && $value == 0) {
            return new ZeroError($source, $this->type, $path);
        }

        return new ConversionValue($value);
    }
}
