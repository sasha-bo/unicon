<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\DefaultError;

abstract class AbstractConverter
{
    public function __construct(
        protected readonly ConversionSettings $settings
    ) {
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError
     */
    final public function convert(mixed $source, string $type, array $path = []): ConversionValue|AbstractError
    {
        return $this->tryStrictMatch($source, $type, $path)
            ?? $this->convertGently($source, $type, $path)
            ?? ($this->settings->isHumanConversionAllowed() ? $this->convertHumanly($source, $type, $path) : null)
            ?? ($this->settings->isForcedConversionAllowed() ? $this->convertForcibly($source, $type, $path) : null)
            ?? $this->createError($source, $type, $path)
        ;
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function tryStrictMatch(mixed $source, string $type, array $path): null|ConversionValue|AbstractError
    {
        return null;
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertGently(mixed $source, string $type, array $path): null|ConversionValue|AbstractError
    {
        return null;
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertHumanly(mixed $source, string $type, array $path): null|ConversionValue|AbstractError
    {
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
        return null;
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return AbstractError
     */
    protected function createError(mixed $source, string $type, array $path): AbstractError
    {
        return new DefaultError($source, $type, $path);
    }
}
