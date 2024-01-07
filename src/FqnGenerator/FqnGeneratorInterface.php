<?php

namespace Unicon\Unicon\FqnGenerator;

/**
 * Generates a fully-qualified name (FQN) for the given class name and context class name FQN.
 * Must understand self and static, must do nothing if FQL received.
 */
interface FqnGeneratorInterface
{
    /**
     * Generates a fully-qualified name (FQN) for the given class name and context class name FQN.
     * Must understand self and static, must do nothing if FQL received.
     * @param string $class
     *      For example, Ccc
     * @param string|null $contextClass
     *      For example, \Aaa\Bbb
     * @return string
     *      For example, \Aaa\Ccc
     */
    public function generate(string $class, string $contextClass = null): string;
}
