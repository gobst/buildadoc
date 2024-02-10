<?php

declare(strict_types = 1);

namespace data\classes;

class parentTestClass
{
    public function testParentClassMethod1(string $testString = 'test'): string
    {
        return 'This is a test method';
    }

    /**
     * This is a test method.
     */
    public function testParentClassMethod2(int $testParam = 0): int
    {
        return 1;
    }
}
