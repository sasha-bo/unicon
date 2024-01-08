<?php

namespace Unicon\Unicon\ConverterFactories\PhpDoc;

use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\Converters\AbstractConverter;
use Unicon\Unicon\Converters\ArrayConverter;
use Unicon\Unicon\Converters\IntegerConverter;
use Unicon\Unicon\Exceptions\UnknownTypehintException;

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
            'array' => self::createArray($phpstanType->genericTypes, $phpDocType, $settings, $selfClass, false),
            'non-empty-array' => self::createArray($phpstanType->genericTypes, $phpDocType, $settings, $selfClass, true),
            'list' => self::createList($phpstanType->genericTypes, $phpDocType, $settings, $selfClass, false),
            'non-empty-list' => self::createList($phpstanType->genericTypes, $phpDocType, $settings, $selfClass, true),
            default => throw new UnknownTypehintException($phpDocType)
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

    /**
     * @param array<TypeNode> $genericTypes
     * @param string $phpDocType
     * @param ConversionSettings $settings
     * @param string $selfClass
     * @param bool $notEmpty
     * @return AbstractConverter
     * @throws UnknownTypehintException
     */
    private static function createArray(array $genericTypes, string $phpDocType, ConversionSettings $settings, ?string $selfClass, bool $notEmpty): AbstractConverter
    {
        if (count($genericTypes) == 0) {
            return new ArrayConverter($settings, $phpDocType);
        } elseif (count($genericTypes) == 1) {
            return new ArrayConverter(
                $settings,
                $phpDocType,
                null,
                PhpDocConverterFactory::create(
                    $genericTypes[0], $settings, $selfClass
                ),
                false,
                $notEmpty
            );
        } elseif (count($genericTypes) == 2) {
            return new ArrayConverter(
                $settings,
                $phpDocType,
                PhpDocConverterFactory::create(
                    $genericTypes[0], $settings, $selfClass
                ),
                PhpDocConverterFactory::create(
                    $genericTypes[1], $settings, $selfClass
                ),
                false,
                $notEmpty
            );
        }

        throw new UnknownTypehintException($phpDocType);
    }

    /**
     * @param array<TypeNode> $genericTypes
     * @param string $phpDocType
     * @param ConversionSettings $settings
     * @param string|null $selfClass
     * @param bool $notEmpty
     * @return AbstractConverter
     * @throws UnknownTypehintException
     */
    private static function createList(array $genericTypes, string $phpDocType, ConversionSettings $settings, ?string $selfClass, bool $notEmpty): AbstractConverter
    {
        if (count($genericTypes) == 0) {
            return new ArrayConverter($settings, $phpDocType, null, null, true);
        } elseif (count($genericTypes) == 1) {
            return new ArrayConverter(
                $settings,
                $phpDocType,
                null,
                PhpDocConverterFactory::create(
                    $genericTypes[0], $settings, $selfClass
                ),
                true,
                $notEmpty
            );
        }

        throw new UnknownTypehintException($phpDocType);
    }
}
