<?php

namespace Unicon\Unicon\ConverterFactories\PhpDoc;

use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConverterFactories\GivenClassConverterFactory;
use Unicon\Unicon\ConverterFactory;
use Unicon\Unicon\Converters\AbstractConverter;
use Unicon\Unicon\Converters\ArrayConverter;
use Unicon\Unicon\Converters\BooleanConverter;
use Unicon\Unicon\Converters\CallableConverter;
use Unicon\Unicon\Converters\FloatConverter;
use Unicon\Unicon\Converters\IntegerConverter;
use Unicon\Unicon\Converters\IterableConverter;
use Unicon\Unicon\Converters\MixedConverter;
use Unicon\Unicon\Converters\NullConverter;
use Unicon\Unicon\Converters\ObjectConverter;
use Unicon\Unicon\Converters\ResourceConverter;
use Unicon\Unicon\Converters\ScalarConverter;
use Unicon\Unicon\Converters\StringConverter;
use Unicon\Unicon\Converters\VoidConverter;

class IdentifierConverterFactory
{
    public static function create(
        IdentifierTypeNode $phpstanType,
        ConversionSettings $settings,
        string $selfClass = null
    ): AbstractConverter {
        return match($phpstanType->name) {
            'int', 'integer' => new IntegerConverter($settings, $phpstanType->name),
            'string' => new StringConverter($settings, $phpstanType->name),
            'bool', 'boolean' => new BooleanConverter($settings, $phpstanType->name),
            'true' => new BooleanConverter($settings, $phpstanType->name, always: true),
            'false' => new BooleanConverter($settings, $phpstanType->name, always: false),
            'null' => new NullConverter($settings, $phpstanType->name),
            'float', 'double' => new FloatConverter($settings, $phpstanType->name),
            'scalar' => new ScalarConverter($settings),
            'array' => new ArrayConverter($settings, $phpstanType->name),
            'list' => new ArrayConverter($settings, $phpstanType->name, isList: true),
            'non-empty-array' => new ArrayConverter($settings, $phpstanType->name, notEmpty: true),
            'non-empty-list' => new ArrayConverter($settings, $phpstanType->name, isList: true, notEmpty: true),
            'array-key' => ConverterFactory::create('int|string', $settings),
            'iterable' => new IterableConverter($settings, $phpstanType->name),
            'callable' => new CallableConverter($settings, $phpstanType->name),
            'resource' => new ResourceConverter($settings, $phpstanType->name),
            'void' => new VoidConverter($settings),
            'object' => new ObjectConverter($settings, $phpstanType->name),
            'mixed' => new MixedConverter($settings, $phpstanType->name),
            'positive-int' => new IntegerConverter($settings, $phpstanType->name, min: 1),
            'negative-int' => new IntegerConverter($settings, $phpstanType->name, max: -1),
            'non-positive-int' => new IntegerConverter($settings, $phpstanType->name, max: 0),
            'non-negative-int' => new IntegerConverter($settings, $phpstanType->name, min: 0),
            'non-zero-int' => new IntegerConverter($settings, $phpstanType->name, nonZero: true),
            default => GivenClassConverterFactory::create($settings, $phpstanType->name, $selfClass)
        };
    }
}
