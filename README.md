# Unicon

Unicon is universal PHP variable converter. The main feature is converting 
arrays to an object of given class. In this case, Unicon follows PhpDoc 
annotations, works recursively to create the object's properties.

## Installation

```composer require unicon/unicon```

## Usage

ConverterFactory creates a converter for the given target type. Target
type may be class FQN, standard php type or types union. Once created, 
the converter may be used few times.

```php
$converter = ConverterFactory::create($targetType);
```

Converter returns ConversionValue|AbstractError. ConversionValue::value 
contains converted value. It may be null if target type is nullable
(null|\MyNamespace\MyClass): in this case null is also success result.

```php
$result = $converter->convert($source);
```

## Example

```php
namespace MyNamespace;

class SimpleClass
{
    private ?string $stringProperty;
    private false|int $falseOrIntegerProperty;
    private \DateTimeInterface $date;
    /** @var bool */
    private mixed $boolPhpDocProperty;
}

$converter = ConverterFactory::create(SimpleClass::class);
$result = $converter->convert([
    'stringProperty' => 'test',
    'falseOrIntegerProperty' => 0,
    'date' => '2013-01-13 12:00:00',
    'boolPhpDocProperty' => 1
]);
```

```$converter->convert(mixed $source)```
<details>
<summary>Result:</summary>

```php
object(Unicon\Unicon\ConversionValue)#11 (1) {
  ["value"]=>
  object(MyNamespace\SimpleClass)#14 (4) {
    ["stringProperty":"MyNamespace\SimpleClass":private]=>
    string(4) "test"
    ["falseOrIntegerProperty":"MyNamespace\SimpleClass":private]=>
    int(0)
    ["date":"MyNamespace\SimpleClass":private]=>
    object(DateTimeImmutable)#29 (3) {
      ["date"]=>
      string(26) "2013-01-13 12:00:00.000000"
      ["timezone_type"]=>
      int(3)
      ["timezone"]=>
      string(11) "Europe/Riga"
    }
    ["boolPhpDocProperty":"MyNamespace\SimpleClass":private]=>
    bool(true)
  }
}
```
</details>

## Settings

ConverterFactory::create has the second optional parameter of
ConversionSettings class:

```php
ConverterFactory::create(
    string $type = 'mixed', 
    ConversionSettings $settings = new ConversionSettings()
)
```

You can create your ConversionSettings and configure it for your needs:

```php
$settings = new ConversionSettings();
$settings->allowHumanConversion();
$settings->allowForcedConversion();
$settings->setStringToDateFormats(['Y-m-d H:i:s']);
$settings->setDateToStringFormat('Y-m-d H:i:s');
```

## Conversion priorities

1. Strict match (1 for int)
2. Gentle casting ('1' for int)
3. Forced casting if turned on (1.1 to 1 for int)

This affect the conversion if the property has few conversion options. For
example:

|                     | ```null``` | ```0```   | ```'0'```   |
|-------------------------|------|-----|-------|
| ```int```               | ```0 ```  | ```0```   | ```0``` |
| ```?int```              | ```null``` | ```0```   | ```0```    |
| ```null\|int\|string``` | ```null``` | ```0```   | ```'0'```   |
| ?string                 | ```null``` | ```'0'``` | ```'0'```   |
| string                  |  ```''```    | ```'0'```  | ```'0'```   |