<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\UnionError;

class UnionConverter extends AbstractConverter
{
    /**
     * @param array<AbstractConverter> $converters
     * @param string $type
     * @param ConversionSettings $settings
     */
    public function __construct(
        private readonly array $converters,
        ConversionSettings $settings,
        string $type
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
        $errors = [];
        foreach ($this->converters as $child) {
            $result = $child->tryStrictMatch($source, $path);
            if ($result instanceof ConversionValue) {
                return $result;
            }
            if ($result instanceof AbstractError) {
                $errors[] = $result;
            }
        }
        return count($errors) > 0 ? new UnionError($source, $this->type, $path, $errors) : null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertGently(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        $errors = [];
        foreach ($this->converters as $child) {
            $result = $child->convertGently($source, $path);
            if ($result instanceof ConversionValue) {
                return $result;
            }
            if ($result instanceof AbstractError) {
                $errors[] = $result;
            }
        }
        return count($errors) > 0 ? new UnionError($source, $this->type, $path, $errors) : null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertHumanly(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        $errors = [];
        foreach ($this->converters as $child) {
            $result = $child->convertHumanly($source, $path);
            if ($result instanceof ConversionValue) {
                return $result;
            }
            if ($result instanceof AbstractError) {
                $errors[] = $result;
            }
        }
        return count($errors) > 0 ? new UnionError($source, $this->type, $path, $errors) : null;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertForcibly(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        $errors = [];
        foreach ($this->converters as $child) {
            $result = $child->convertForcibly($source, $path);
            if ($result instanceof ConversionValue) {
                return $result;
            }
            if ($result instanceof AbstractError) {
                $errors[] = $result;
            }
        }
        return count($errors) > 0 ? new UnionError($source, $this->type, $path, $errors) : null;
    }
}
