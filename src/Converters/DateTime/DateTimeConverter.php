<?php

namespace Unicon\Unicon\Converters\DateTime;

use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\Converters\AbstractConverter;

class DateTimeConverter extends AbstractConverter
{
    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, array $path): ?ConversionValue
    {
        return $source instanceof \DateTimeInterface
            ? new ConversionValue(\DateTime::createFromInterface($source))
            : null;
    }

    public function convertGently(mixed $source, array $path): ?ConversionValue
    {
        if (is_string($source)) {
            if (preg_match('/^[0-9]+$/', $source) && $this->settings->isTimestampToDateConversionAllowed()) {
                return new ConversionValue((new \DateTime())->setTimestamp((int) $source));
            } else {
                $source = trim((string) preg_replace('/\s+/', ' ', $source));
                foreach ($this->settings->getStringToDateFormats() as $dateFormat) {
                    $date = \DateTime::createFromFormat($dateFormat, $source);
                    if ($date instanceof \DateTime) {
                        return new ConversionValue($date);
                    }
                }
            }
        } elseif (is_int($source)) {
            if ($this->settings->isTimestampToDateConversionAllowed()) {
                return new ConversionValue((new \DateTime())->setTimestamp($source));
            }
        }

        return null;
    }

    public function convertForcibly(mixed $source, array $path): ?ConversionValue
    {
        if (is_string($source) || is_null($source)) {
            try {
                return new ConversionValue(new \DateTime((string) $source));
            } catch (\Exception) {}
        } elseif (is_int($source)) {
            return new ConversionValue((new \DateTime())->setTimestamp($source));
        }

        return null;
    }
}
