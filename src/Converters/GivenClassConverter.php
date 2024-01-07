<?php

namespace Unicon\Unicon\Converters;

use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConversionValue;

class GivenClassConverter extends AbstractConverter
{
    public function __construct(
        ConversionSettings $settings,
        private string $classFqn
    ) {
        parent::__construct($settings, $this->classFqn);
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    public function tryStrictMatch(mixed $source, array $path): ?ConversionValue
    {
        return is_array($source) ? new ConversionValue($source) : null;
    }
}
