<?php

namespace Unicon\Unicon;

use Unicon\Unicon\FqnGenerator\FqnGenerator;
use Unicon\Unicon\FqnGenerator\FqnGeneratorInterface;

class ConversionSettings
{
    private bool $humanConversionAllowed = false;
    private bool $forcedConversionAllowed = false;
    private bool $propertiesMustBeInitialized = false;
    private string $dateToStringFormat = 'Y-m-d H:i:s';

    /**
     * @var array<string>
     */
    private array $stringToDateFormats = [
        'Y-m-d H:i:s',
        \DateTimeInterface::ATOM,
        \DateTimeInterface::COOKIE,
        \DateTimeInterface::RFC822,
        \DateTimeInterface::RFC850,
        \DateTimeInterface::RFC1036,
        \DateTimeInterface::RFC1123,
        \DateTimeInterface::RFC7231,
        \DateTimeInterface::RFC2822,
        \DateTimeInterface::RFC3339,
        \DateTimeInterface::RFC3339_EXTENDED,
        \DateTimeInterface::RSS,
        \DateTimeInterface::W3C,
    ];

    private bool $timestampToDateConversionAllowed = false;

    private FqnGeneratorInterface $fqnGenerator;

    public function isHumanConversionAllowed(): bool
    {
        return $this->humanConversionAllowed;
    }

    public function isForcedConversionAllowed(): bool
    {
        return $this->forcedConversionAllowed;
    }

    public function propertiesMustBeInitialized(): bool
    {
        return $this->propertiesMustBeInitialized;
    }

    public function getDateToStringFormat(): string
    {
        return $this->dateToStringFormat;
    }

    public function isTimestampToDateConversionAllowed(): bool
    {
        return $this->timestampToDateConversionAllowed;
    }

    /**
     * @return array<string>
     */
    public function getStringToDateFormats(): array
    {
        return $this->stringToDateFormats;
    }

    public function getFqnGenerator(): FqnGeneratorInterface
    {
        if (!isset($this->fqnGenerator)) {
            $this->fqnGenerator = new FqnGenerator();
        }
        return $this->fqnGenerator;
    }

    public function allowHumanConversion(bool $value = true): static
    {
        $this->humanConversionAllowed = $value;

        return $this;
    }

    public function allowForcedConversion(bool $value = true): static
    {
        $this->forcedConversionAllowed = $value;

        return $this;
    }

    public function checkIfAllPropertiesAreInitialized(bool $value = true): static
    {
        $this->propertiesMustBeInitialized = $value;

        return $this;
    }

    public function setDateToStringFormat(string $format): static
    {
        $this->dateToStringFormat = $format;

        return $this;
    }

    /**
     * @param array<string> $formats
     * @return $this
     */
    public function setStringToDateFormats(array $formats): static
    {
        $this->stringToDateFormats = $formats;

        return $this;
    }

    public function allowTimestampToDateConversion(bool $value = true): static
    {
        $this->timestampToDateConversionAllowed = $value;

        return $this;
    }

    public function setFqnGenerator(FqnGeneratorInterface $fqnGenerator): static
    {
        $this->fqnGenerator = $fqnGenerator;

        return $this;
    }
}
