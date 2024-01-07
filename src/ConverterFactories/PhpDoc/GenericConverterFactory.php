<?php

namespace Unicon\Unicon\ConverterFactories\PhpDoc;

use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\Converters\AbstractConverter;
use Unicon\Unicon\Converters\IntegerConverter;
use Unicon\Unicon\Converters\UnsupportedConverter;

class GenericConverterFactory
{
    public static function create(
        GenericTypeNode $phpstanType,
        ConversionSettings $settings,
        string $phpDocType,
        string $selfClass = null
    ): AbstractConverter {
        $mainType = $phpstanType->type;
        return match($mainType->name) {
            'int', 'integer' => self::createInteger($phpstanType->genericTypes, $phpDocType, $settings),
            default => new UnsupportedConverter($settings, $phpDocType)
        };
    }

    /**
     * @param array<TypeNode> $genericTypes
     * @param string $phpDocType
     * @param ConversionSettings $settings
     * @return AbstractConverter
     */
    private static function createInteger(array $genericTypes, string $phpDocType, ConversionSettings $settings): AbstractConverter
    {
        $parameters = [
            'min' => null,
            'max' => null
        ];

        foreach ($genericTypes as $no => $node) {
            if ($node instanceof ConstTypeNode) {
                $nodeExpr = $node->constExpr;
                if ($nodeExpr instanceof ConstExprIntegerNode && is_numeric($nodeExpr->value)) {
                    $parameters[$no == 0 ? 'min' : 'max'] = (int) $nodeExpr->value;
                }
            }
        }

        return new IntegerConverter($settings, $phpDocType, ...$parameters);
    }
}
