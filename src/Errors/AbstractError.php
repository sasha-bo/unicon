<?php

namespace Unicon\Unicon\Errors;

abstract class AbstractError
{
    /**
     * @param mixed $value
     * @param array<string|int> $path
     */
    public function __construct(
        public mixed $value,
        public string $typeHint,
        public array $path
    ) {
    }
}
