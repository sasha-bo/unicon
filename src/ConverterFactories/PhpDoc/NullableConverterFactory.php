<?php

namespace Unicon\Unicon\ConverterFactories\PhpDoc;

use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\Converters\AbstractConverter;
use Unicon\Unicon\Converters\NullConverter;
use Unicon\Unicon\Converters\UnionConverter;

class NullableConverterFactory
{
    public static function create(
        NullableTypeNode $phpstanType,
        ConversionSettings $settings,
        string $phpDocType,
        string $selfClass = null
    ): AbstractConverter {
        return new UnionConverter([
            ConverterFactory::create($phpstanType->type, $settings, $phpDocType, $selfClass),
            new NullConverter($settings, $phpDocType)
        ], $settings, $phpDocType);
    }
}
