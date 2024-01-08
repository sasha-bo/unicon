<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\EmptyArrayError;

class IterableConverter extends ArrayConverter
{
    public function tryStrictMatch(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        if (is_iterable($source)) {
            if ($this->notEmpty && !$this->checkIsNotEmpty($source)) {
                return new EmptyArrayError($source, $this->type, $path);
            }
            if (
                (!$this->isList || $this->checkIsList($source))
                && $this->checkTypes($source)
            ) {
                return new ConversionValue($source);
            }
        }

        return null;
    }
}
