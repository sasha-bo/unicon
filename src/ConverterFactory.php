<?php

namespace Unicon\Unicon;

use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\TypeParser as PHPStanTypeParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use Unicon\Unicon\Constraints\ComparingConstraint;
use Unicon\Unicon\Constraints\FalseConstraint;
use Unicon\Unicon\Constraints\TrueConstraint;
use Unicon\Unicon\ConverterFactories\PhpDoc\IdentifierConverterFactory;
use Unicon\Unicon\ConverterFactories\PhpDoc\ConverterFactory as PhpDocConverterFactory;
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
use Unicon\Unicon\Converters\VoidConverter;

class ConverterFactory
{
    public static function create(
        ConversionSettings $settings = new ConversionSettings(),
        string $type = 'mixed',
        string $selfClass = null
    ): ?AbstractConverter {
        return PhpDocConverterFactory::create(
            self::parsePhpDocType($type),
            $settings,
            $type,
            $selfClass
        );
    }

    private static function parsePhpDocType(string $phpDocType): TypeNode
    {
        static $phpstanLexer = new Lexer();
        static $phpstanTypeParser = new PHPStanTypeParser(new ConstExprParser());
        return $phpstanTypeParser->parse(
            new TokenIterator(
                $phpstanLexer->tokenize($phpDocType)
            )
        );
    }
}
