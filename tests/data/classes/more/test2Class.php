<?php

declare(strict_types = 1);

namespace data\classes\more;

use data\classes\testInterface;
use data\classes\testInterface2;

class test2Class extends parentTest2Class implements testInterface, testInterface2
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
