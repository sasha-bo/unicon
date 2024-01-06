<?php

namespace Unicon\Unicon\ConverterFactories\PhpDoc;

use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\Type\ConstTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
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
use Unicon\Unicon\Converters\UnsupportedConverter;
use Unicon\Unicon\Converters\VoidConverter;

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
            'int', 'integer' => self::createInteger($phpstanType->genericTypes, $settings),
            default => new UnsupportedConverter($settings)
        };
    }

    /**
     * @param array<TypeNode> $genericTypes
     * @param ConversionSettings $settings
     * @return AbstractConverter
     */
    private static function createInteger(array $genericTypes, ConversionSettings $settings): AbstractConverter
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

        return new IntegerConverter($settings, ...$parameters);
    }
}
