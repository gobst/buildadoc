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

use Collection\ModifierCollection;
use Contract\Formatter\Component\TableFormatterInterface;
use Dto\Common\Modifier;
use Dto\Method\Method;
use Generator\Documentation\Class\Page\Component\Method\MethodTableGenerator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class MethodTableGeneratorTest extends TestCase
{
    private TableFormatterInterface&MockObject $tableFormatter;
    private MethodTableGenerator $methodTableGenerator;

    public function setUp(): void
    {
        $this->tableFormatter = $this->getMockBuilder(TableFormatterInterface::class)->getMock();
        $this->methodTableGenerator = new MethodTableGenerator($this->tableFormatter);
    }

    #[DataProvider('methodTableGeneratorTestDataProvider')]
    #[TestDox('generate() method works correctly with parameters $method, "$format", $headerLabels')]
    public function testGenerate(Method $method, string $format, array $headerLabels): void
    {
        $this->tableFormatter->expects(self::once())
            ->method('formatTable')
            ->willReturn('');

        $this->methodTableGenerator->generate($method, $format, $headerLabels);
    }

    #[TestDox('generate() method fails on InvalidArgumentException')]
    public function testGenerateWillFailOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $modifiers = new ModifierCollection();
        $publicModifierDto = Modifier::create('public');
        $modifiers->add($publicModifierDto);
        $methodDto = Method::create(
            'testMethodWithoutPHPDoc',
            $modifiers,
            'string',
            'testClass'
        );

        $this->tableFormatter->expects(self::never())
            ->method('formatTable');

        $this->methodTableGenerator->generate($methodDto, '', []);
    }

    public static function methodTableGeneratorTestDataProvider(): array
    {
        $modifiers = new ModifierCollection();
        $publicModifierDto = Modifier::create('public');
        $modifiers->add($publicModifierDto);
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
