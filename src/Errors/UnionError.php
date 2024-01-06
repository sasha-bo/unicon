<?php

namespace Unicon\Unicon\Errors;

readonly class UnionError extends AbstractError
{
    /**
     * @param mixed $value
     * @param string $typeHint
     * @param array<string|int> $path
     * @param array<AbstractError> $errors
     */
    public function __construct(
        mixed $value,
        string $typeHint,
        array $path,
        public array $errors
    ) {
        parent::__construct($value, $typeHint, $path);
    }
}
