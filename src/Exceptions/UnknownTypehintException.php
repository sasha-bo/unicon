<?php

namespace Unicon\Unicon\Exceptions;

class UnknownTypehintException  extends UniconException
{
    public function __construct(string $typehint)
    {
        parent::__construct('Unknown typehint: '.$typehint);
    }
}
