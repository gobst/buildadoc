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

use Contract\Decorator\Component\TableDecoratorInterface;
use Dto\Common\Modifier;
use Dto\Method\Method;
use Generator\Documentation\Class\Page\Component\Method\MethodTableGenerator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(MethodTableGenerator::class)]
#[UsesClass(Collection::class)]
#[UsesClass(Modifier::class)]
#[UsesClass(Method::class)]
class MethodTableGeneratorTest extends TestCase
{
    private TableDecoratorInterface&MockObject $tableFormatter;
    private MethodTableGenerator $methodTableGenerator;

    public function setUp(): void
    {
        $this->tableFormatter = $this->getMockBuilder(TableDecoratorInterface::class)->getMock();
        $this->methodTableGenerator = new MethodTableGenerator($this->tableFormatter);
    }

    #[DataProvider('methodTableGeneratorTestDataProvider')]
    #[TestDox('generate() method works correctly with parameters $method, $format, $headerLabels')]
    public function testGenerate(Method $method, string $format, array $headerLabels): void
    {
        $this->tableFormatter->expects(self::once())
            ->method('format')
            ->willReturn('');

        $this->methodTableGenerator->generate($method, $format, $headerLabels);
    }

    #[TestDox('generate() method fails on InvalidArgumentException')]
    public function testGenerateWillFailOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $publicModifierDto = Modifier::create('public');
        $modifiers->push($publicModifierDto);
        $methodDto = Method::create(
            'testMethodWithoutPHPDoc',
            $modifiers,
            'string',
            'testClass'
        );

        $this->tableFormatter->expects(self::never())
            ->method('format');

        $this->methodTableGenerator->generate($methodDto, '', []);
    }

    public static function methodTableGeneratorTestDataProvider(): array
    {
        /** @var Collection<int, Modifier> $modifiers */
        $modifiers = Collection::make();
        $publicModifierDto = Modifier::create('public');
        $modifiers->push($publicModifierDto);
        $methodDto = Method::create(
            'testMethodWithoutPHPDoc',
            $modifiers,
            'string',
            'testClass'
        );

        return [
            'testcase 1' => [$methodDto, 'dokuwiki', ['heading 1', 'heading 2', 'heading 3', 'heading 4']],
        ];
    }
}
