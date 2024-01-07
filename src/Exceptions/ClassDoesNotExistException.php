<?php

namespace Unicon\Unicon\Exceptions;

class ClassDoesNotExistException extends UniconException
{
    public function __construct(public string $class)
    {
        parent::__construct('Class '.$this->class.' doesn\'t exist');
    }
}
