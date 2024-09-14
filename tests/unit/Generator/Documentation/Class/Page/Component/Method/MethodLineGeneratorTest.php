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

namespace unit\Generator\Documentation\Class\Page\Component\Method;

use Contract\Service\Class\Data\MethodDataServiceInterface;
use Dto\Common\Modifier;
use Dto\Method\Method;
use Generator\Documentation\Class\Page\Component\Method\MethodLineGenerator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class MethodLineGeneratorTest extends TestCase
{
    private MethodDataServiceInterface&MockObject $methodDataService;
    private MethodLineGenerator $methodLineGenerator;

    public function setUp(): void
    {
        $this->methodDataService = $this->getMockBuilder(MethodDataServiceInterface::class)->getMock();
        $this->methodLineGenerator = new MethodLineGenerator($this->methodDataService);
    }

    #[DataProvider('methodLineGeneratorTestDataProvider')]
    #[TestDox('generate() method works correctly with parameters $method, $withModifiers, $bold')]
    public function testGenerate($method, $withModifiers, $bold): void
    {
        $this->methodDataService->expects(self::once())
            ->method('fetchMethodSignature')
            ->willReturn('');

        $this->methodLineGenerator->generate($method, $withModifiers, $bold);
    }

    public static function methodLineGeneratorTestDataProvider(): array
    {
        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $modifiers->push(Modifier::create('public'));

        $method = Method::create('testMethod', $modifiers, 'string', 'testClass');

        return [
            'testcase 1' => [$method, true, true],
            'testcase 2' => [$method, false, false],
            'testcase 3' => [$method, true, false],
            'testcase 4' => [$method, false, true],
        ];
    }
}
