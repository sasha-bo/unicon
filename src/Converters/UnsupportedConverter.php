<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\Constraints\AbstractConstraint;
use Unicon\Unicon\ConversionResult;
use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\ConversionErrorType;
use Unicon\Unicon\Errors\UnsupportedError;
use Unicon\Unicon\SignedConversionValue;

class UnsupportedConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return UnsupportedError
     */
    public function tryStrictMatch(mixed $source, string $type, array $path): UnsupportedError
    {
        return new UnsupportedError($source, $type, $path);
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return UnsupportedError
     */
    protected function convertGently(mixed $source, string $type, array $path): UnsupportedError
    {
        return new UnsupportedError($source, $type, $path);
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return UnsupportedError
     */
    protected function convertHumanly(mixed $source, string $type, array $path): UnsupportedError
    {
        return new UnsupportedError($source, $type, $path);
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return UnsupportedError
     */
    protected function convertForcibly(mixed $source, string $type, array $path): UnsupportedError
    {
        return new UnsupportedError($source, $type, $path);
    }
}
