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
    private const CONVERTOR_CLASSES = [
        '\\stdClass' => StdClassConverter::class,
        '\\DateTime' => DateTimeConverter::class,
        '\\DateTimeImmutable' => DateTimeImmutableConverter::class,
        '\\DateTimeInterface' => DateTimeInterfaceConverter::class,
    ];

    /** @var array<int, array<string, AbstractConverter>> */
    private static array $converters = [];

    public static function create(
        ConversionSettings $settings,
        string $class,
        string $contextClass = null
    ): AbstractConverter {
        $settingsId = spl_object_id($settings);
        $classFqn = $settings->getFqnGenerator()->generate($class, $contextClass);
        if (!isset(self::$converters[$settingsId][$classFqn])) {
            if (isset(self::CONVERTOR_CLASSES[$classFqn])) {
                $converter = new (self::CONVERTOR_CLASSES[$classFqn])($settings, $classFqn);
                if (!$converter instanceof AbstractConverter) {
                    throw new \Exception('Broken converter for '.$classFqn);
                }
                self::$converters[$settingsId][$classFqn] = $converter;
            } else {
                self::$converters[$settingsId][$classFqn] = new GivenClassConverter($settings, $classFqn);
            }
        }

        return self::$converters[$settingsId][$classFqn];
    }
}
