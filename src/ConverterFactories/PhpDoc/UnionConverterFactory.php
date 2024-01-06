<?php

namespace Unicon\Unicon\ConverterFactories\PhpDoc;

use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
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
use Unicon\Unicon\Converters\UnionConverter;
use Unicon\Unicon\Converters\VoidConverter;

class UnionConverterFactory
{
    public static function create(
        UnionTypeNode $phpstanType,
        ConversionSettings $settings,
        string $phpDocType,
        string $selfClass = null
    ): AbstractConverter {
        $converters = [];
        foreach ($phpstanType->types as $oneOfTypes) {
            $converters[] = ConverterFactory::create($oneOfTypes, $settings, $phpDocType, $selfClass);
        }
        return new UnionConverter($converters, $settings);
    }
}
