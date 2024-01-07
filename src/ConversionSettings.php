<?php

namespace Unicon\Unicon;

use Unicon\Unicon\FqnGenerator\FqnGenerator;
use Unicon\Unicon\FqnGenerator\FqnGeneratorInterface;

class ConversionSettings
{
    private bool $humanConversionAllowed = false;
    private bool $forcedConversionAllowed = false;
    private string $dateToStringFormat = 'Y-m-d H:i:s';
    private FqnGeneratorInterface $fqnGenerator;

    public function isHumanConversionAllowed(): bool
    {
        return $this->humanConversionAllowed;
    }

    public function isForcedConversionAllowed(): bool
    {
        return $this->forcedConversionAllowed;
    }

    public function getDateToStringFormat(): string
    {
        return $this->dateToStringFormat;
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

    public function setDateToStringFormat(string $format): static
    {
        $this->dateToStringFormat = $format;

        return $this;
    }

    public function setFqnGenerator(FqnGeneratorInterface $fqnGenerator): static
    {
        $this->fqnGenerator = $fqnGenerator;

        return $this;
    }
}
