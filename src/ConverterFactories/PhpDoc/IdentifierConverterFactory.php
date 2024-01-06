<?php

namespace Unicon\Unicon\ConverterFactories\PhpDoc;

use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\TypeParser as PHPStanTypeParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use Unicon\Unicon\Constraints\ComparingConstraint;
use Unicon\Unicon\Constraints\FalseConstraint;
use Unicon\Unicon\Constraints\TrueConstraint;
use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\Converters\AbstractConverter;
use Unicon\Unicon\Converters\ArrayConverter;
use Unicon\Unicon\Converters\BooleanConverter;
use Unicon\Unicon\Converters\CallableConverter;
use Unicon\Unicon\Converters\ConstraintConverter;
use Unicon\Unicon\Converters\FloatConverter;
use Unicon\Unicon\Converters\IntegerConverter;
use Unicon\Unicon\Converters\IterableConverter;
use Unicon\Unicon\Converters\MixedConverter;
use Unicon\Unicon\Converters\NullConverter;
use Unicon\Unicon\Converters\ObjectConverter;
use Unicon\Unicon\Converters\ResourceConverter;
use Unicon\Unicon\Converters\ScalarConverter;
use Unicon\Unicon\Converters\StringConverter;
use Unicon\Unicon\Converters\UnsupportedConverter;
use Unicon\Unicon\Converters\VoidConverter;

class IdentifierConverterFactory
{
    public static function create(
        IdentifierTypeNode $phpstanType,
        ConversionSettings $settings,
        string $phpDocType,
        string $selfClass = null
    ): AbstractConverter {
        return match($phpstanType->name) {
            'int', 'integer' => new IntegerConverter($settings),
            'string' => new StringConverter($settings),
            'bool', 'boolean' => new BooleanConverter($settings),
            'true' => new BooleanConverter($settings, always: true),
            'false' => new BooleanConverter($settings, always: false),
            'null' => new NullConverter($settings),
            'float', 'double' => new FloatConverter($settings),
            'scalar' => new ScalarConverter($settings),
            'array' => new ArrayConverter($settings),
            'iterable' => new IterableConverter($settings),
            'callable' => new CallableConverter($settings),
            'resource' => new ResourceConverter($settings),
            'void' => new VoidConverter($settings),
            'object' => new ObjectConverter($settings),
            'mixed' => new MixedConverter($settings),
            'positive-int' => new IntegerConverter($settings, min: 1),
            'negative-int' => new IntegerConverter($settings, max: -1),
            'non-positive-int' => new IntegerConverter($settings, max: 0),
            'non-negative-int' => new IntegerConverter($settings, min: 0),
            'non-zero-int' => new IntegerConverter($settings, nonZero: true),
            default => new UnsupportedConverter($settings)
        };
    }
}
