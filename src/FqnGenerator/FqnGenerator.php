<?php

namespace Unicon\Unicon\FqnGenerator;

class FqnGenerator implements FqnGeneratorInterface
{
    public function generate(string $class, string $contextClass = null): string
    {
        if (str_starts_with($class, '\\')) {
            return $class;
        }
        if ($class == 'static' || $class == 'self') {
            if (is_null($contextClass)) {
                throw new \Exception('Can\'t use '.$class.' with null $contextClass');
            }
            return $contextClass;
        }
        return $this->getNamespace($contextClass).'\\'.$class;
    }

    private function getNamespace(?string $selfClass): string
    {
        if (!is_null($selfClass)) {
            $parsed = explode('\\', $selfClass);
            if (is_array($parsed)) {
                array_pop($parsed);
                return implode('\\', $parsed);
            }
        }

        return '';
    }
}
