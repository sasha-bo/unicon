<?php

namespace Unicon\Unicon\Errors;

class TooSmallError extends AbstractError
{
    /**
     * @param mixed $value
     * @param string $typeHint
     * @param array<string|int> $path
     * @param int $min
     * @param bool $mayBeEqual
     */
    public function __construct(
        mixed $value,
        string $typeHint,
        array $path,
        public int $min,
        public bool $mayBeEqual = true
    ) {
        parent::__construct($value, $typeHint, $path);
    }
}
