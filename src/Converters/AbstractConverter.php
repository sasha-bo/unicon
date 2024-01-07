<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\DefaultError;

abstract class AbstractConverter
{
    public function __construct(
        protected readonly ConversionSettings $settings,
        protected string $type
    ) {
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError
     */
    final public function convert(mixed $source, array $path = []): ConversionValue|AbstractError
    {
        return $this->tryStrictMatch($source, $path)
            ?? $this->convertGently($source, $path)
            ?? ($this->settings->isHumanConversionAllowed() ? $this->convertHumanly($source, $path) : null)
            ?? ($this->settings->isForcedConversionAllowed() ? $this->convertForcibly($source, $path) : null)
            ?? $this->createError($source, $path)
        ;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function tryStrictMatch(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        return null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertGently(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        return null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertHumanly(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        return null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertForcibly(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        return null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return AbstractError
     */
    protected function createError(mixed $source, array $path): AbstractError
    {
        return new DefaultError($source, $this->type, $path);
    }
}
