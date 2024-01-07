<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionResult;
use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\DefaultError;
use Unicon\Unicon\Errors\UnsupportedError;
use Unicon\Unicon\Errors\VoidError;

class VoidConverter extends AbstractConverter
{
    public function __construct(
        ConversionSettings $settings
    ) {
        parent::__construct($settings, 'void');
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return VoidError
     */
    protected function tryStrictMatch(mixed $source, array $path): VoidError
    {
        return $this->createError($source, $path);
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return VoidError
     */
    protected function convertGently(mixed $source, array $path): VoidError
    {
        return $this->createError($source, $path);
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return VoidError
     */
    protected function convertHumanly(mixed $source, array $path): VoidError
    {
        return $this->createError($source, $path);
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return VoidError
     */
    protected function convertForcibly(mixed $source, array $path): VoidError
    {
        return $this->createError($source, $path);
    }

    protected function createError(mixed $source, array $path): VoidError
    {
        return new VoidError($source, $this->type, $path);
    }
}
