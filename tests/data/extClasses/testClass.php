<?php

declare(strict_types = 1);

namespace data\extClasses;

include_once 'testInterface.php';
include_once 'testInterface2.php';

class testClass extends parentTestClass implements testInterface, testInterface2
{
    private string $testparam1 = '';
    private string $testparam2 = '';
    private const testConst = 'testval1';
    public const testConst2 = 2;

    public function testMethodWithoutPHPDoc(string $testString = 'test'): string
    {
        return 'This is a test method';
    }

    /**
     * This is a test method.
     */
    public function testMethodWithPHPDoc(int $testInt = 0): int
    {
        return 1;
    }
}
