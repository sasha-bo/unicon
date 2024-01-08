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

Result:

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