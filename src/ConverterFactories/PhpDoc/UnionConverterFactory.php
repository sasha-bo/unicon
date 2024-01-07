<?php

namespace Unicon\Unicon\ConverterFactories\PhpDoc;

use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\Converters\AbstractConverter;
use Unicon\Unicon\Converters\UnionConverter;

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
            $converters[] = PhpDocConverterFactory::create($oneOfTypes, $settings, $selfClass);
        }
        return new UnionConverter($converters, $settings, $phpDocType);
    }
}
