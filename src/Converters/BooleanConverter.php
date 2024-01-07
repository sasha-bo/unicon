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
        string $type,
        private readonly ?bool $always = null
    ) {
        parent::__construct($settings, $type);
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    public function tryStrictMatch(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        return is_bool($source) ? $this->validate($source, $source, $path) : null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertGently(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        return match($source) {
            '', 0, '0' => $this->validate(false, $source, $path),
            1, '1' => $this->validate(true, $source, $path),
            default => null
        };
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertHumanly(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        if (is_string($source)) {
            return match(strtolower($source)) {
                'false', 'no', 'f', 'n' => $this->validate(false, $source, $path),
                'true', 'yes', 't', 'y' => $this->validate(true, $source, $path),
                default => null
            };
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
        return $this->validate((bool) $source, $source, $path);
    }

    /**
     * @param bool $value
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError
     */
    private function validate(bool $value, mixed $source, array $path): ConversionValue|AbstractError
    {
        if (!is_null($this->always) && $value != $this->always) {
            return new TrueFalseError($source, $this->type, $path, $this->always);
        }

        return new ConversionValue($value);
    }
}
