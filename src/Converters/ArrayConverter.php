<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\EmptyArrayError;
use Unicon\Unicon\Errors\KeyDuplicationError;

class ArrayConverter extends AbstractConverter
{
    public function __construct(
        ConversionSettings $settings,
        string $type,
        private ?AbstractConverter $keyConverter = null,
        private ?AbstractConverter $valueConverter = null,
        protected bool $isList = false,
        protected bool $notEmpty = false
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
        if (is_array($source)) {
            if ($this->notEmpty && !$this->checkIsNotEmpty($source)) {
                return new EmptyArrayError($source, $this->type, $path);
            }
            if (
                (!$this->isList || $this->checkIsList($source))
                && $this->checkTypes($source)
            ) {
                return new ConversionValue($source);
            }
        }

        return null;
    }

    public function convertGently(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        if (is_null($source)) {
            if ($this->settings->isNullToEmptyArrayConversionAllowed()) {
                if ($this->notEmpty) {
                    return new EmptyArrayError($source, $this->type, $path);
                }
                return new ConversionValue([]);
            }
        } elseif (is_iterable($source)) {
            if ($this->notEmpty && !$this->checkIsNotEmpty($source)) {
                return new EmptyArrayError($source, $this->type, $path);
            }
            $convertedArray = [];
            foreach ($source as $key => $value) {
                if (is_null($this->keyConverter)) {
                    $convertedKey = $key;
                } else {
                    $result = $this->keyConverter->convert($key, [...$path, $key]);
                    if ($result instanceof AbstractError) {
                        return $result;
                    }
                    $convertedKey = $result->value;
                    if (!is_int($convertedKey) && !is_string($convertedKey)) {
                        throw new \Exception($convertedKey.' can\'t be array key');
                    }
                }
                if (array_key_exists($convertedKey, $convertedArray)) {
                    return new KeyDuplicationError($source, $this->type, $path, $convertedKey);
                }
                if (is_null($this->valueConverter)) {
                    $convertedValue = $value;
                } else {
                    $result = $this->valueConverter->convert($value, [...$path, $key]);
                    if ($result instanceof AbstractError) {
                        return $result;
                    }
                    $convertedValue = $result->value;
                }
                $convertedArray[$convertedKey] = $convertedValue;
            }
            if ($this->isList && !$this->checkIsList($convertedArray)) {
                ksort($convertedArray);
                $convertedArray = array_values($convertedArray);
            }
            return new ConversionValue($convertedArray);
        }

        return null;
    }

    /**
     * @param iterable<mixed> $array
     * @return bool
     */
    protected function checkIsList(iterable $array): bool
    {
        $assessKey = 0;
        foreach ($array as $key => $value) {
            if ($key !== $assessKey) {
                return false;
            }
            $assessKey ++;
        }

        return true;
    }

    /**
     * @param iterable<mixed> $array
     * @return bool
     */
    protected function checkIsNotEmpty(iterable $array): bool
    {
        foreach ($array as $value) {
            return true;
        }

        return false;
    }

    /**
     * @param iterable<mixed> $array
     * @return bool
     */
    protected function checkTypes(iterable $array): bool
    {
        if (!is_null($this->keyConverter)) {
            foreach ($array as $key => $value) {
                if (!$this->keyConverter->tryStrictMatch($key, []) instanceof ConversionValue) {
                    return false;
                }
            }
        }
        if (!is_null($this->valueConverter)) {
            foreach ($array as $value) {
                if (!$this->valueConverter->tryStrictMatch($value, []) instanceof ConversionValue) {
                    return false;
                }
            }
        }

        return true;
    }
}
