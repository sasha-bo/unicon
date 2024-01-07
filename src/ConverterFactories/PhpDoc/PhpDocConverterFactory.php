<?php

namespace Unicon\Unicon\ConverterFactories\PhpDoc;

use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\Converters\AbstractConverter;
use Unicon\Unicon\Converters\UnsupportedConverter;
use Unicon\Unicon\PhpDocParser;

class PhpDocConverterFactory
{
    public static function create(
        TypeNode $phpstanType,
        ConversionSettings $settings,
        string $selfClass = null
    ): AbstractConverter {
        return match (true) {
            $phpstanType instanceof IdentifierTypeNode => IdentifierConverterFactory::create(
                $phpstanType,
                $settings,
                $selfClass
            ),
            $phpstanType instanceof UnionTypeNode => UnionConverterFactory::create(
                $phpstanType,
                $settings,
                PhpDocParser::printType($phpstanType),
                $selfClass
            ),
            $phpstanType instanceof NullableTypeNode => NullableConverterFactory::create(
                $phpstanType,
                $settings,
                PhpDocParser::printType($phpstanType),
                $selfClass
            ),
            $phpstanType instanceof GenericTypeNode => GenericConverterFactory::create(
                $phpstanType,
                $settings,
                PhpDocParser::printType($phpstanType),
                $selfClass
            ),
            default => new UnsupportedConverter($settings, PhpDocParser::printType($phpstanType))
        };
    }
}
