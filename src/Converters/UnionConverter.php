<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\Constraints\AbstractConstraint;
use Unicon\Unicon\ConversionResult;
use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\ConversionErrorType;
use Unicon\Unicon\Errors\UnionError;
use Unicon\Unicon\SignedConversionValue;

class UnionConverter extends AbstractConverter
{
    /**
     * @param array<AbstractConverter> $converters
     * @param ConversionSettings $settings
     */
    public function __construct(
        private readonly array $converters,
        ConversionSettings $settings
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
        $errors = [];
        foreach ($this->converters as $child) {
            $result = $child->tryStrictMatch($source, $type, $path);
            if ($result instanceof ConversionValue) {
                return $result;
            }
            if ($result instanceof AbstractError) {
                $errors[] = $result;
            }
        }
        return count($errors) > 0 ? new UnionError($source, $type, $path, $errors) : null;
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertGently(mixed $source, string $type, array $path): null|ConversionValue|AbstractError
    {
        $errors = [];
        foreach ($this->converters as $child) {
            $result = $child->convertGently($source, $type, $path);
            if ($result instanceof ConversionValue) {
                return $result;
            }
            if ($result instanceof AbstractError) {
                $errors[] = $result;
            }
        }
        return count($errors) > 0 ? new UnionError($source, $type, $path, $errors) : null;
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertHumanly(mixed $source, string $type, array $path): null|ConversionValue|AbstractError
    {
        $errors = [];
        foreach ($this->converters as $child) {
            $result = $child->convertHumanly($source, $type, $path);
            if ($result instanceof ConversionValue) {
                return $result;
            }
            if ($result instanceof AbstractError) {
                $errors[] = $result;
            }
        }
        return count($errors) > 0 ? new UnionError($source, $type, $path, $errors) : null;
    }

    /**
     * @param mixed $source
     * @param string $type
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError|null
     */
    protected function convertForcibly(mixed $source, string $type, array $path): null|ConversionValue|AbstractError
    {
        $errors = [];
        foreach ($this->converters as $child) {
            $result = $child->convertForcibly($source, $type, $path);
            if ($result instanceof ConversionValue) {
                return $result;
            }
            if ($result instanceof AbstractError) {
                $errors[] = $result;
            }
        }
        return count($errors) > 0 ? new UnionError($source, $type, $path, $errors) : null;
    }
}
