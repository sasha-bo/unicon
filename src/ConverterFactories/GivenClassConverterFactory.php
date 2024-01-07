<?php

namespace Unicon\Unicon\ConverterFactories;

use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\Converters\AbstractConverter;
use Unicon\Unicon\Converters\DateTime\DateTimeConverter;
use Unicon\Unicon\Converters\DateTime\DateTimeImmutableConverter;
use Unicon\Unicon\Converters\DateTime\DateTimeInterfaceConverter;
use Unicon\Unicon\Converters\GivenClassConverter;
use Unicon\Unicon\Converters\StdClassConverter;

class GivenClassConverterFactory
{
    /** @var array<string, string> */
    private static array $converterClasses = [
        '\\stdClass' => StdClassConverter::class,
        '\\DateTime' => DateTimeConverter::class,
        '\\DateTimeImmutable' => DateTimeImmutableConverter::class,
        '\\DateTimeInterface' => DateTimeInterfaceConverter::class,
    ];

    /** @var array<string, AbstractConverter> */
    private static array $converters = [];

    public static function create(
        ConversionSettings $settings,
        string $class,
        string $contextClass = null
    ): AbstractConverter {
        $classFqn = $settings->getFqnGenerator()->generate($class, $contextClass);
        if (!isset(self::$converters[$classFqn])) {
            if (isset(self::$converterClasses[$classFqn])) {
                $converter = new self::$converterClasses[$classFqn]($settings, $classFqn);
                if (!$converter instanceof AbstractConverter) {
                    throw new \Exception('Broken converter for '.$classFqn);
                }
                self::$converters[$classFqn] = $converter;
            } else {
                self::$converters[$classFqn] = new GivenClassConverter($settings, $classFqn);
            }
        }

        return self::$converters[$classFqn];
    }
}
