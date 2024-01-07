<?php

namespace Unicon\Unicon;

use Unicon\Unicon\ConverterFactories\PhpDoc\PhpDocConverterFactory as PhpDocConverterFactory;
use Unicon\Unicon\Converters\AbstractConverter;

class ConverterFactory
{
    public static function create(
        string $type = 'mixed',
        ConversionSettings $settings = new ConversionSettings(),
        string $selfClass = null
    ): AbstractConverter {
        return PhpDocConverterFactory::create(
            PhpDocParser::parseType($type),
            $settings,
            $selfClass
        );
    }
}
