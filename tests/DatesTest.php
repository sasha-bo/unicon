<?php

use PHPUnit\Framework\TestCase;
use Unicon\Unicon\ConversionSettings;
use Unicon\Unicon\ConverterFactory;
use Unicon\Unicon\Errors\AbstractError;

final class DatesTest extends TestCase
{
    private const FORMAT = 'Y-m-d_H:i:s';

    public function testTimestamp(): void
    {
        $now = new \DateTime();
        $settings = new ConversionSettings();
        $settings->allowTimestampToDateConversion();

        foreach ([\DateTime::class, \DateTimeImmutable::class, \DateTimeInterface::class] as $class) {
            $converter = ConverterFactory::create($class);
            $result = $converter->convert($now->getTimestamp());
            $this->assertInstanceOf(AbstractError::class, $result);

            $converter = ConverterFactory::create($class, $settings);
            $result = $converter->convert($now->getTimestamp());
            $this->assertInstanceOf($class, $result->value);
            $this->assertSame(
                $now->format(self::FORMAT),
                $result->value->format(self::FORMAT)
            );
        }
    }

    public function testString(): void
    {
        $now = new \DateTime();
        $formattedNow = $now->format(self::FORMAT);
        $settings = new ConversionSettings();
        $settings->setStringToDateFormats([self::FORMAT]);

        foreach ([\DateTime::class, \DateTimeImmutable::class, \DateTimeInterface::class] as $class) {
            $converter = ConverterFactory::create($class, $settings);
            $result = $converter->convert($formattedNow);
            $this->assertInstanceOf($class, $result->value);
            $this->assertSame($formattedNow, $result->value->format(self::FORMAT));
        }
    }

    public function testInterface(): void
    {
        $nowDateTime = new \DateTime();
        $nowDateTimeImmutable = \DateTimeImmutable::createFromMutable($nowDateTime);

        $result = ConverterFactory::create(\DateTime::class)->convert($nowDateTimeImmutable);
        $this->assertInstanceOf(\DateTime::class, $result->value);
        $this->assertSame(
            $nowDateTime->format(self::FORMAT),
            $result->value->format(self::FORMAT)
        );

        $result = ConverterFactory::create(\DateTimeImmutable::class)->convert($nowDateTime);
        $this->assertInstanceOf(\DateTimeImmutable::class, $result->value);
        $this->assertSame(
            $nowDateTime->format(self::FORMAT),
            $result->value->format(self::FORMAT)
        );
    }

    public function testForced(): void
    {
        $now = new \DateTime();
        $settings = new ConversionSettings();
        $settings->allowForcedConversion();

        foreach ([\DateTime::class, \DateTimeImmutable::class, \DateTimeInterface::class] as $class) {
            $converter = ConverterFactory::create($class);
            $result = $converter->convert('now');
            $this->assertInstanceOf(AbstractError::class, $result);

            $converter = ConverterFactory::create($class, $settings);
            $result = $converter->convert('now');
            $this->assertInstanceOf($class, $result->value);
            $this->assertSame(
                $now->format('d.m.y'),
                $result->value->format('d.m.y')
            );
        }
    }
}
