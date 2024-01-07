<?php

use PHPUnit\Framework\TestCase;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\ConverterFactory;
use Unicon\Unicon\TestClasses\FirstClass;

include_once(__DIR__ . '/classes/FirstClass.php');

final class ObjectsTest extends TestCase
{
    public function testObject(): void
    {
        $converter = ConverterFactory::create('object');

        $result = $converter->convert(
            [
                'intVar' => 1,
                'stringVar' => 'aaa',
            ],
        );

        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertInstanceOf(\stdClass::class, $result->value);
        $this->assertSame(1, $result->value->intVar);
        $this->assertSame('aaa', $result->value->stringVar);
    }

    public function testStdClass(): void
    {
        $converter = ConverterFactory::create(\stdClass::class);

        $result = $converter->convert(
            [
                'intVar' => 1,
                'stringVar' => 'aaa',
            ],
        );

        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertInstanceOf(\stdClass::class, $result->value);
        $this->assertSame(1, $result->value->intVar);
        $this->assertSame('aaa', $result->value->stringVar);
    }

    public function testStrictMatch(): void
    {
        $object = new FirstClass(666, '666');

        $converter = ConverterFactory::create(FirstClass::class);

        $result = $converter->convert($object);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame($object, $result->value);
    }
}
