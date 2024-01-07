<?php

use PHPUnit\Framework\TestCase;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\ConverterFactory;

final class UnionTest extends TestCase
{
    public function testStrictPreferenceInUnion(): void
    {
        foreach ([null, 666, 66.6, true, '666', new \DateTime()] as $value) {
            $result = ConverterFactory::create('null|int|float|bool|string|\DateTimeInterface')->convert($value);
            $this->assertInstanceOf(ConversionValue::class, $result);
            $this->assertSame($value, $result->value);
        }
    }
}
