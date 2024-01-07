<?php

use PHPUnit\Framework\TestCase;
use Unicon\Unicon\ConverterFactory;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\ConversionSettings;

final class TypesTest extends TestCase
{
    public function testMixedTypeHint(): void
    {
        $converter = ConverterFactory::create('mixed');

        foreach ([666, 66.6, true, false, '666', new \DateTime(), null] as $value) {
            $result = $converter->convert($value);
            $this->assertInstanceOf(ConversionValue::class, $result);
            $this->assertSame($value, $result->value);
        }
    }

    public function testNoForcedCastingByDefault(): void
    {
        $converter = ConverterFactory::create('boolean');

        foreach ([666, 66.6, '666'] as $value) {
            $result = $converter->convert($value);
            $this->assertInstanceOf(AbstractError::class, $result);
        }
    }

    public function testForcedCasting(): void
    {
        $settings = new ConversionSettings();
        $settings->allowForcedConversion();

        $booleanConverter = ConverterFactory::create('boolean', $settings);
        $integerConverter = ConverterFactory::create('integer', $settings);

        foreach ([666, 66.6, '666'] as $value) {
            $result = $booleanConverter->convert($value);
            $this->assertInstanceOf(ConversionValue::class, $result);
            $this->assertSame(true, $result->value);
        }
        foreach ([1.2, '1aa', true] as $value) {
            $result = $integerConverter->convert($value);
            $this->assertInstanceOf(ConversionValue::class, $result);
            $this->assertSame(1, $result->value);
        }
    }

    public function testBooleanGentleCasting(): void
    {
        $booleanConverter = ConverterFactory::create('boolean');

        foreach ([1, '1'] as $value) {
            $result = $booleanConverter->convert($value);
            $this->assertInstanceOf(ConversionValue::class, $result);
            $this->assertSame(true, $result->value);
        }
        foreach ([0, '0', ''] as $value) {
            $result = $booleanConverter->convert($value);
            $this->assertInstanceOf(ConversionValue::class, $result);
            $this->assertSame(false, $result->value);
        }
    }

    public function testStringGentleCasting(): void
    {
        $converter = ConverterFactory::create('string');

        $result = $converter->convert(666);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame('666', $result->value);

        $result = $converter->convert(66.6);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame('66.6', $result->value);

        $result = $converter->convert(null);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame('', $result->value);
    }

    public function testIntegerGentleCasting(): void
    {
        $converter = ConverterFactory::create('int');

        $result = $converter->convert('666');
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame(666, $result->value);

        $result = $converter->convert('-666');
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame(-666, $result->value);

        $result = $converter->convert(666.0);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame(666, $result->value);

        $result = $converter->convert(-666.0);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame(-666, $result->value);

        $result = $converter->convert(666.6);
        $this->assertInstanceOf(AbstractError::class, $result);

        $result = $converter->convert('');
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame(0, $result->value);

        $result = $converter->convert(null);
        $this->assertInstanceOf(ConversionValue::class, $result);
        $this->assertSame(0, $result->value);
    }
}
