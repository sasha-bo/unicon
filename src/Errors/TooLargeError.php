<?php

namespace Unicon\Unicon\Errors;

class TooLargeError extends AbstractError
{
    /**
     * @param mixed $value
     * @param array<string|int> $path
     */
    public function __construct(
        mixed $value,
        string $typeHint,
        array $path,
        public int $max,
        public bool $mayBeEqual = true
    ) {
        parent::__construct($value, $typeHint, $path);
    }
}
