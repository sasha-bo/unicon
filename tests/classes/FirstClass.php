<?php

namespace Unicon\Unicon\TestClasses;

#[\AllowDynamicProperties]
class FirstClass
{
    public string $stringProperty = 'default';
    /** @var positive-int|string|null $mixedProperty  */
    private int $integerProperty = 1;
    protected mixed $mixedProperty = null;

    /**
     * @param int<1,100> $param1
     */
    public function __construct(
        private int $integerParameter = 6,
        private string $stringParameter = 'ddd'
    ) {
    }
}
