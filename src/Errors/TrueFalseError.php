<?php

namespace Unicon\Unicon\Errors;

readonly class TrueFalseError extends AbstractError
{
    public function __construct(
        mixed $value,
        string $typeHint,
        array $path,
        public bool $mustBe
    ) {
        parent::__construct($value, $typeHint, $path);
    }
}
