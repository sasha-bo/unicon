<?php

namespace Unicon\Unicon\Converters\DateTime;

use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Converters\AbstractConverter;

class DateTimeInterfaceConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, array $path): ?ConversionValue
    {
        return $source instanceof \DateTimeInterface ? new ConversionValue($source) : null;
    }

    public function convertGently(mixed $source, array $path): ?ConversionValue
    {
        if (is_string($source)) {
            if (preg_match('/^[0-9]+$/', $source) && $this->settings->isTimestampToDateConversionAllowed()) {
                return new ConversionValue((new \DateTimeImmutable())->setTimestamp((int) $source));
            } else {
                $source = trim((string) preg_replace('/\s+/', ' ', $source));
                foreach ($this->settings->getStringToDateFormats() as $dateFormat) {
                    $date = \DateTimeImmutable::createFromFormat($dateFormat, $source);
                    if ($date instanceof \DateTimeImmutable) {
                        return new ConversionValue($date);
                    }
                }
            }
        } elseif (is_int($source)) {
            if ($this->settings->isTimestampToDateConversionAllowed()) {
                return new ConversionValue((new \DateTimeImmutable())->setTimestamp($source));
            }
        }

        return null;
    }

    public function convertForcibly(mixed $source, array $path): ?ConversionValue
    {
        if (is_string($source) || is_null($source)) {
            try {
                return new ConversionValue(new \DateTimeImmutable((string) $source));
            } catch (\Exception) {}
        } elseif (is_int($source)) {
            return new ConversionValue((new \DateTimeImmutable())->setTimestamp($source));
        }

        return null;
    }
}
