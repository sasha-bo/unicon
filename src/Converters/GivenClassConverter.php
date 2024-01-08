<?php

namespace Unicon\Unicon\Converters;

use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConversionValue;
use Unicon\Unicon\ConverterFactories\PhpDoc\PhpDocConverterFactory;
use Unicon\Unicon\ConverterFactories\Reflection\TypeConverterFactory;
use Unicon\Unicon\ConverterFactory;
use Unicon\Unicon\Errors\AbstractError;
use Unicon\Unicon\Errors\ConstructorParamNotSet;
use Unicon\Unicon\Errors\DefaultError;
use Unicon\Unicon\Errors\DynamicPropertyError;
use Unicon\Unicon\Exceptions\IntersectionException;
use Unicon\Unicon\PhpDocParser;

class GivenClassConverter extends AbstractConverter
{
    private bool $inited = false;
    private AbstractConverter $arrayConverter;

    /** @var array<AbstractConverter> */
    private array $propertiesConverters = [];

    /** @var array<string, mixed> */
    private array $constructorDefaultValues = [];

    /** @var array<string> */
    private array $constructorParametersNames = [];

    /**
     * @param ConversionSettings $settings
     * @param \ReflectionClass<object> $reflection
     * @throws IntersectionException
     */
    public function __construct(
        ConversionSettings $settings,
        private \ReflectionClass $reflection
    ) {
        parent::__construct($settings, $reflection->name);
    }

    private function init(): void
    {
        if ($this->inited) {
            return;
        }
        $this->arrayConverter = ConverterFactory::create('array', $this->settings, '\\'.$this->reflection->name);
        // Constructor @param attributes
        $constructorPhpDoc = $this->reflection->getConstructor()?->getDocComment();
        if (is_string($constructorPhpDoc)) {
            foreach (PhpDocParser::parseParams($constructorPhpDoc) as $name => $typeNode) {
                if (!isset($this->propertiesConverters[$name])) {
                    $this->propertiesConverters[$name] = PhpDocConverterFactory::create(
                        $typeNode,
                        $this->settings,
                        '\\'.$this->reflection->name
                    );
                }
            }
        }
        // Constructor parameters
        foreach ($this->reflection->getConstructor()?->getParameters() ?? [] as $parameter) {
            if (!isset($this->propertiesConverters[$parameter->name])) {
                $this->propertiesConverters[$parameter->name] = TypeConverterFactory::create(
                    $parameter->getType(),
                    $this->settings,
                    '\\'.$this->reflection->name
                );
            }
            if ($parameter->isDefaultValueAvailable()) {
                $this->constructorDefaultValues[$parameter->name] = $parameter->getDefaultValue();
            } elseif ($parameter->allowsNull()) {
                $this->constructorDefaultValues[$parameter->name] = null;
            }
            $this->constructorParametersNames[] = $parameter->name;
        }
        // Properties
        foreach ($this->reflection->getProperties() as $property) {
            if (!isset($this->propertiesConverters[$property->name])) {
                $phpDoc = $property->getDocComment();
                if (is_string($phpDoc)) {
                    $typeNode = PhpDocParser::parseVar($phpDoc, $property->name);
                    if ($typeNode instanceof TypeNode) {
                        $this->propertiesConverters[$property->name] = PhpDocConverterFactory::create(
                            $typeNode,
                            $this->settings,
                            '\\'.$this->reflection->name
                        );
                    }
                }
                if (!isset($this->propertiesConverters[$property->name])) {
                    $this->propertiesConverters[$property->name] = TypeConverterFactory::create(
                        $property->getType(),
                        $this->settings,
                        '\\'.$this->reflection->name
                    );
                }
            }
        }
        $this->inited = true;
    }

    /**
     * @param mixed $source
     * @param array<string|int> $path
     * @return ConversionValue|null
     */
    protected function tryStrictMatch(mixed $source, array $path): ?ConversionValue
    {
        return is_object($source) && $this->reflection->isInstance($source)
            ? new ConversionValue($source) : null;
    }

    protected function convertGently(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        return is_array($source) ? $this->convertArrayToGivenClass($source, $path) : null;
    }

    protected function convertForcibly(mixed $source, array $path): null|ConversionValue|AbstractError
    {
        return is_object($source) ? $this->convertObjectToGivenClass($source, $path) : null;
    }

    /**
     * @param array<mixed> $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError
     */
    private function convertArrayToGivenClass(
        array $source,
        array $path
    ): ConversionValue|AbstractError {
        $this->init();
        $restOfSourceValues = $source;
        // Constructor parameters
        $constructorParameters = [];
        foreach ($this->constructorParametersNames as $name) {
            if (key_exists($name, $source)) {
                $converter = $this->propertiesConverters[$name];
                $result = $converter->convert($source[$name], [...$path, $name]);
                if ($result instanceof ConversionValue) {
                    $constructorParameters[$name] = $result->value;
                } else {
                    return $result;
                }
                unset($restOfSourceValues[$name]);
            } elseif (key_exists($name, $this->constructorDefaultValues)) {
                $constructorParameters[$name] = $this->constructorDefaultValues[$name];
            } else {
                return new ConstructorParamNotSet($source, $this->reflection->name, $name, $path);
            }
        }
        // Creating object
        try {
            $object = $this->reflection->newInstance(...$constructorParameters);
        } catch (\Exception) {
            return new DefaultError($source, $this->reflection->name, $path);
        }
        // Properties
        if (count($restOfSourceValues) > 0) {
            $objectReflection = new \ReflectionObject($object);
            foreach ($objectReflection->getProperties() as $property) {
                if (key_exists($property->name, $restOfSourceValues)) {
                    $converter = $this->propertiesConverters[$property->name];
                    $result = $converter->convert($restOfSourceValues[$property->name], [...$path, $property->name]);
                    if ($result instanceof ConversionValue) {
                        $property->setValue($object, $result->value);
                        unset($restOfSourceValues[$property->name]);
                    } else {
                        return $result;
                    }
                }
            }
            if (count($restOfSourceValues) > 0) {
                $dynamicPropertiesAllowed = $object instanceof \stdClass;
                if (!$dynamicPropertiesAllowed) {
                    foreach ($objectReflection->getAttributes() as $attribute) {
                        if ($attribute->getName() == 'AllowDynamicProperties') {
                            $dynamicPropertiesAllowed = true;
                            break;
                        }
                    }
                }
                if ($dynamicPropertiesAllowed) {
                    foreach ($restOfSourceValues as $name => $value) {
                        $object->$name = $value;
                    }
                } else {
                    foreach ($restOfSourceValues as $name => $value) {
                        return new DynamicPropertyError($source, $this->reflection->name, $name, $path);
                    }
                }
            }
        }
        // Return
        return new ConversionValue($object);
    }

    /**
     * @param object $source
     * @param array<string|int> $path
     * @return ConversionValue|AbstractError
     */
    private function convertObjectToGivenClass(
        object $source,
        array $path
    ): ConversionValue|AbstractError {
        $this->init();
        $arrayResult = $this->arrayConverter->convert($source);
        if ($arrayResult instanceof ConversionValue) {
            /** @var array<mixed> $array */
            $array = $arrayResult->value;
            return $this->convertArrayToGivenClass($array, $path);
        } else {
            return $arrayResult;
        }
    }
}
