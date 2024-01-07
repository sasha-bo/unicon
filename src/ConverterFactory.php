<?php

namespace Unicon\Unicon;

use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser as PHPStanTypeParser;
use Unicon\Unicon\ConverterFactories\PhpDoc\ConverterFactory as PhpDocConverterFactory;
use Unicon\Unicon\Converters\AbstractConverter;

class ConverterFactory
{
    public static function create(
        string $type = 'mixed',
        ConversionSettings $settings = new ConversionSettings(),
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
