<?php

namespace Unicon\Unicon\Errors;

class KeyDuplicationError extends AbstractError
{
    public function __construct(mixed $value, string $typeHint, array $path, public mixed $key)
    {
        parent::__construct($value, $typeHint, $path);
    }
}
