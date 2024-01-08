<?php

use PHPUnit\Framework\TestCase;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\ConverterFactory;
use Unicon\Unicon\Errors\DynamicPropertyError;
use Unicon\Unicon\TestClasses\ClassWithObjectProperty;
use Unicon\Unicon\TestClasses\RecursiveClass;
use Unicon\Unicon\TestClasses\SimpleClass;
use Unicon\Unicon\TestClasses\SimpleClassWithDynamicProperties;

include_once(__DIR__ . '/classes/SimpleClass.php');
include_once(__DIR__ . '/classes/SimpleClassWithDynamicProperties.php');
include_once(__DIR__ . '/classes/ClassWithObjectProperty.php');
include_once(__DIR__ . '/classes/RecursiveClass.php');

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
        $object = new SimpleClass();

        $converter = ConverterFactory::create(SimpleClass::class);

        $result = $converter->convert($object);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame($object, $result->value);
    }

    public function testSimpleClass(): void
    {
        $converter = ConverterFactory::create(SimpleClass::class);

        $result = $converter->convert([
            'stringProperty' => 'new',
            'integerProperty' => 1
        ]);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertInstanceOf(SimpleClass::class, $result->value);
        $this->assertSame('new', $result->value->stringProperty);
        $this->assertSame(1, $result->value->integerProperty);
    }

    public function testDynamicProperties(): void
    {
        $dpConverter = ConverterFactory::create(SimpleClassWithDynamicProperties::class);
        $noDpConverter = ConverterFactory::create(SimpleClass::class);
        $source = [
            'stringProperty' => 'new',
            'integerProperty' => 1,
            'dynamicProperty' => 'xxx'
        ];

        $result = $noDpConverter->convert($source);
        $this->assertInstanceOf(DynamicPropertyError::class, $result);

        $result = $dpConverter->convert($source);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertInstanceOf(SimpleClassWithDynamicProperties::class, $result->value);
        $this->assertSame('new', $result->value->stringProperty);
        $this->assertSame(1, $result->value->integerProperty);
        $this->assertSame('xxx', $result->value->dynamicProperty);
    }

    public function testClassWithObjectProperty(): void
    {
        $converter = ConverterFactory::create(ClassWithObjectProperty::class);

        $result = $converter->convert([
            'stringProperty' => 'ppp',
            'simpleObject' => [
                'stringProperty' => 'new',
                'integerProperty' => 1
            ]
        ]);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertInstanceOf(ClassWithObjectProperty::class, $result->value);
        $this->assertSame('ppp', $result->value->stringProperty);
        $this->assertInstanceOf(SimpleClass::class, $result->value->simpleObject);
        $this->assertSame('new', $result->value->simpleObject->stringProperty);
        $this->assertSame(1, $result->value->simpleObject->integerProperty);

        $result = $converter->convert([
            'stringProperty' => 'ppp',
            'simpleObject' => ''
        ]);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertInstanceOf(ClassWithObjectProperty::class, $result->value);
        $this->assertSame('ppp', $result->value->stringProperty);
        $this->assertSame(null, $result->value->simpleObject);
    }

    public function testRecursiveProperties(): void
    {
        $converter = ConverterFactory::create(RecursiveClass::class);
        $result = $converter->convert([
            'namedInstance' => [
                'namedInstance' => [
                    'namedInstance' => ['namedInstance' => null, 'selfInstance' => null],
                    'selfInstance' => null
                ],
                'selfInstance' => ['namedInstance' => null, 'selfInstance' => null],
            ],
            'selfInstance' => [
                'namedInstance' => [
                    'namedInstance' => null,
                    'selfInstance' => ['namedInstance' => null, 'selfInstance' => null]
                ],
                'selfInstance' => ['namedInstance' => null, 'selfInstance' => null],
            ]
        ]);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertInstanceOf(RecursiveClass::class, $result->value);
        $this->assertInstanceOf(RecursiveClass::class, $result->value->namedInstance);
        $this->assertInstanceOf(RecursiveClass::class, $result->value->selfInstance);
        $this->assertInstanceOf(RecursiveClass::class, $result->value->namedInstance->namedInstance);
        $this->assertInstanceOf(RecursiveClass::class, $result->value->namedInstance->selfInstance);
        $this->assertInstanceOf(RecursiveClass::class, $result->value->selfInstance->namedInstance);
        $this->assertInstanceOf(RecursiveClass::class, $result->value->selfInstance->selfInstance);
        $this->assertInstanceOf(RecursiveClass::class, $result->value->namedInstance->namedInstance->namedInstance);
        $this->assertSame(null, $result->value->namedInstance->namedInstance->selfInstance);
        $this->assertInstanceOf(RecursiveClass::class, $result->value->selfInstance->namedInstance->selfInstance);
        $this->assertSame(null, $result->value->selfInstance->namedInstance->namedInstance);
    }
}
