<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionResult;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\DefaultError;
use Unicon\Unicon\Errors\UnsupportedError;

class VoidConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return DefaultError
     */
    protected function tryStrictMatch(mixed $source, string $type, array $path): DefaultError
    {
        return new DefaultError($source, $type, $path);
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return DefaultError
     */
    protected function convertGently(mixed $source, string $type, array $path): DefaultError
    {
        return new DefaultError($source, $type, $path);
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return DefaultError
     */
    protected function convertHumanly(mixed $source, string $type, array $path): DefaultError
    {
        return new DefaultError($source, $type, $path);
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return DefaultError
     */
    protected function convertForcibly(mixed $source, string $type, array $path): DefaultError
    {
        return new DefaultError($source, $type, $path);
    }
}
