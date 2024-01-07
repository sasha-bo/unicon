<?php

use PHPUnit\Framework\TestCase;
use Unicon\Unicon\FqnGenerator\FqnGenerator;

final class FqnTest extends TestCase
{
    public function testSelf(): void
    {
        $generator = new FqnGenerator();
        $this->assertSame(
            '\\Aaa\\Bbb',
            $generator->generate('self', '\\Aaa\\Bbb')
        );
        $caughtException = null;
        try {
            $generator->generate('self');
        } catch (\Exception $exception) {
            $caughtException = $exception;
        }
        $this->assertInstanceOf(\Exception::class, $caughtException);
    }

    public function testStatic(): void
    {
        $generator = new FqnGenerator();
        $this->assertSame(
            '\\Aaa\\Bbb',
            $generator->generate('static', '\\Aaa\\Bbb')
        );
        $caughtException = null;
        try {
            $generator->generate('static');
        } catch (\Exception $exception) {
            $caughtException = $exception;
        }
        $this->assertInstanceOf(\Exception::class, $caughtException);
    }

    public function testFqn(): void
    {
        $generator = new FqnGenerator();
        $this->assertSame(
            '\\Ccc\\Ddd',
            $generator->generate('\\Ccc\\Ddd', '\\Aaa\\Bbb')
        );
    }

    public function testContext(): void
    {
        $generator = new FqnGenerator();
        $this->assertSame(
            '\\Aaa\\Ccc',
            $generator->generate('Ccc', '\\Aaa\\Bbb')
        );
    }

    public function testNoContext(): void
    {
        $generator = new FqnGenerator();
        $this->assertSame(
            '\\Ccc',
            $generator->generate('Ccc')
        );
    }
}
