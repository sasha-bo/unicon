<?php

use PHPUnit\Framework\TestCase;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\ConverterFactory;
use Unicon\Unicon\Errors\EmptyArrayError;

final class ArrayTest extends TestCase
{
    public function testNoChangesCase(): void
    {
        $source = [1 => 'aaa', 'bbb' => 'ccc'];
        $result = ConverterFactory::create('array')->convert($source);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame($source, $result->value);
    }

    public function testKeysConversion(): void
    {
        $source = [1 => 'aaa', '0' => 'ccc'];
        $result = ConverterFactory::create('array<int,string>')->convert($source);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame([1 => 'aaa', 0 => 'ccc'], $result->value);
    }

    public function testValuesConversionToString(): void
    {
        $source = [1 => 1, '0' => 2];
        $result = ConverterFactory::create('array<string>')->convert($source);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame([1 => '1', 0 => '2'], $result->value);
    }

    public function testConversionToList(): void
    {
        $source = ['1' => 1, '0' => 2];
        $result = ConverterFactory::create('list')->convert($source);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame([0 => 2, 1 => 1], $result->value);
        $result = ConverterFactory::create('list<string>')->convert($source);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame([0 => '2', 1 => '1'], $result->value);
    }

    public function testNonEmpty(): void
    {
        $result = ConverterFactory::create('non-empty-list')->convert([]);
        $this->assertInstanceOf(EmptyArrayError::class, $result);
        $result = ConverterFactory::create('non-empty-array')->convert([]);
        $this->assertInstanceOf(EmptyArrayError::class, $result);
        $result = ConverterFactory::create('non-empty-list<string>')->convert([]);
        $this->assertInstanceOf(EmptyArrayError::class, $result);
        $result = ConverterFactory::create('non-empty-array<string>')->convert([]);
        $this->assertInstanceOf(EmptyArrayError::class, $result);
        $result = ConverterFactory::create('non-empty-array<int,string>')->convert([]);
        $this->assertInstanceOf(EmptyArrayError::class, $result);
    }

    public function testArrayOfDates(): void
    {
        $date = '2013-01-13 12:00:00';
        foreach ([\DateTime::class, \DateTimeImmutable::class, \DateTimeInterface::class] as $class) {
            $result = ConverterFactory::create('list<'.$class.'>')->convert([$date]);
            $this->assertInstanceOf(ConversionValue::class, $result);
            $this->assertIsArray($result->value);
            $this->assertInstanceOf($class, $result->value[0]);
            $this->assertSame($date, $result->value[0]->format('Y-m-d H:i:s'));
        }
    }

    public function testArrayOfArrays(): void
    {
        $typehint = 'array<string, array<int, string>>';
        $source = [['777' => 777]];
        $expected = ['0' => [777 => '777']];
        $result = ConverterFactory::create($typehint)->convert($source);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame($expected, $result->value);
    }
}
