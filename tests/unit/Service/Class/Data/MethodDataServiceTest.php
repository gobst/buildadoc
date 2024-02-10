<?php
/**
 * This file is part of BuildADoc.
 *
 * (c) Guido Obst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types = 1);

namespace unit\Service\Class\Data;

use Collection\MethodParameterCollection;
use Collection\ModifierCollection;
use Contract\Service\Class\Data\DescriptionDataServiceInterface;
use Contract\Service\Class\Data\ModifierDataServiceInterface;
use Dto\Common\Modifier;
use Dto\Method\Method;
use Dto\Method\MethodParameter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\Class\Data\MethodDataService;

final class MethodDataServiceTest extends TestCase
{
    private ModifierDataServiceInterface&MockObject $modifierDataService;
    private DescriptionDataServiceInterface&MockObject $descDataService;
    private MethodDataService $methodDataService;

    public function setUp(): void
    {
        $this->modifierDataService = $this->getMockBuilder(ModifierDataServiceInterface::class)->getMock();
        $this->descDataService = $this->getMockBuilder(DescriptionDataServiceInterface::class)->getMock();
        $this->methodDataService = new MethodDataService($this->modifierDataService, $this->descDataService);
    }

    // @todo
    /*public function testGetMethods(): void
    {
    }*/

    // @todo
    /*public function testFetchInheritedMethods(): void
    {
    }*/

    // @todo
    /*public function testFetchMethodSignatureWithModifiers(): void
    {
    }*/

    #[DataProvider('methodSignatureTestDataProvider')]
    #[TestDox('fetchMethodSignature() method works correctly with parameters $method, $withModifiers')]
    public function testFetchMethodSignature(Method $method, bool $withModifiers): void
    {
        $expected = $withModifiers === true
            ? 'public testMethodWithoutPHPDoc(string $testString = \'test\'): string'
            : 'testMethodWithoutPHPDoc(string $testString = \'test\'): string';

        $actual = $this->methodDataService->fetchMethodSignature($method, $withModifiers);

        $this->assertEquals($expected, $actual);
    }

    // @todo
    /*public function testFetchMethod(): void
    {
    }*/

    public static function methodSignatureTestDataProvider(): array
    {
        $modifiers = new ModifierCollection();
        $publicModifierDto = Modifier::create('public');
        $modifiers->add($publicModifierDto);

        $parameters = new MethodParameterCollection();
        $parameterDto = MethodParameter::create('testString', 'string');
        $parameterDto = $parameterDto->withDefaultValue('test');
        $parameters->add($parameterDto);
        $methodDto = Method::create('testMethodWithoutPHPDoc', $modifiers, 'string', 'testClass');
        $methodDto = $methodDto->withParameters($parameters);

        return [
            'testcase 1' => [$methodDto, true],
            'testcase 2' => [$methodDto, false],
        ];
    }
}
