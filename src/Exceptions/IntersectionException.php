<?php

namespace Unicon\Unicon\Exceptions;

class IntersectionException extends UniconException
{
    public function __construct(string $message = 'Unicon can\\t work with intersection types')
    {
        parent::__construct($message);
    }
}
