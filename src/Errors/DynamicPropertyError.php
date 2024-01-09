<?php

namespace Unicon\Unicon\Errors;

class DynamicPropertyError extends AbstractError
{
    /**
     * @param array<mixed> $source
     * @param string $class
     * @param mixed $propertyValue
     * @param array<string|int> $path
     */
    public function __construct(
        array $source,
        string $class,
        public mixed $propertyValue,
        array $path,
    ) {
        parent::__construct($source, $class, $path);
    }
}
