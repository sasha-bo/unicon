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

class ConverterFactory
{
    public static function create(
        TypeNode $phpstanType,
        ConversionSettings $settings,
        string $phpDocType,
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
                $phpDocType,
                $selfClass
            ),
            $phpstanType instanceof NullableTypeNode => NullableConverterFactory::create(
                $phpstanType,
                $settings,
                $phpDocType,
                $selfClass
            ),
            $phpstanType instanceof GenericTypeNode => GenericConverterFactory::create(
                $phpstanType,
                $settings,
                $phpDocType,
                $selfClass
            ),
            default => new UnsupportedConverter($settings, $phpDocType)
        };
    }
}
