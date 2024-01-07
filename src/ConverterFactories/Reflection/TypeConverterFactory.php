<?php

namespace Unicon\Unicon\ConverterFactories\Reflection;

use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConverterFactory;
use Unicon\Unicon\Converters\AbstractConverter;
use Unicon\Unicon\Converters\MixedConverter;
use Unicon\Unicon\Converters\NullConverter;
use Unicon\Unicon\Converters\UnionConverter;
use Unicon\Unicon\Exceptions\IntersectionException;

class TypeConverterFactory
{
    /**
     * @throws IntersectionException
     */
    public static function create(
        ?\ReflectionType $type,
        ConversionSettings $settings,
        string $contextClass
    ): AbstractConverter {
        return match (true) {
            $type instanceof \ReflectionIntersectionType => throw new IntersectionException(),
            $type instanceof \ReflectionNamedType => self::createNamed($type, $settings, $contextClass),
            $type instanceof \ReflectionUnionType => self::createUnion($type, $settings, $contextClass),
            default => new MixedConverter($settings, 'mixed')
        };
    }

    private static function createNamed(\ReflectionNamedType $type, ConversionSettings $settings, string $contextClass): AbstractConverter
    {
        if ($type->allowsNull()) {
            return new UnionConverter([
                ConverterFactory::create($type->getName(), $settings, $contextClass),
                new NullConverter($settings, 'null')
            ], $settings, $type->getName());
        } else {
            return ConverterFactory::create($type->getName(), $settings, $contextClass);
        }
    }

    private static function createUnion(\ReflectionUnionType $type, ConversionSettings $settings, string $contextClass): AbstractConverter
    {
        $converters = [];
        $name = [];
        foreach ($type->getTypes() as $subType) {
            if ($subType instanceof \ReflectionIntersectionType) {
                throw new IntersectionException();
            } else {
                $converters[] = self::createNamed($subType, $settings, $contextClass);
            }
            $name[] = $subType->getName();
        }
        return new UnionConverter($converters, $settings, implode('|', $name));
    }
}
