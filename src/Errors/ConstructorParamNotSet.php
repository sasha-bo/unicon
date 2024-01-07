<?php

namespace Unicon\Unicon\Errors;

readonly class ConstructorParamNotSet extends AbstractError
{
    /**
     * @param array<mixed> $source
     * @param string $class
     * @param string $parameter
     * @param array<string|int> $path
     */
    public function __construct(
        array $source,
        string $class,
        public string $parameter,
        array $path,
    ) {
        parent::__construct($source, $class, $path);
    }
}
