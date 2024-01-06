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
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertGently(mixed $source, string $type, array $path): null|ConversionValue|AbstractError
    {
        return match($source) {
            '', 0, '0' => $this->validate(false, $source, $type, $path),
            1, '1' => $this->validate(true, $source, $type, $path),
            default => null
        };
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertHumanly(mixed $source, string $type, array $path): null|ConversionValue|AbstractError
    {
        if (is_string($source)) {
            return match(strtolower($source)) {
                'false', 'no', 'f', 'n' => $this->validate(false, $source, $type, $path),
                'true', 'yes', 't', 'y' => $this->validate(true, $source, $type, $path),
                default => null
            };
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
        return $this->validate((bool) $source, $source, $type, $path);
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
