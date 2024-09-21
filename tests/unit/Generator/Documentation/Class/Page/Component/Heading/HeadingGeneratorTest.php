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

namespace unit\Generator\Documentation\Class\Page\Component\Heading;

use Contract\Decorator\TextDecoratorFactoryInterface;
use Contract\Decorator\TextDecoratorInterface;
use Generator\Documentation\Class\Page\Component\Heading\HeadingGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(HeadingGenerator::class)]
final class HeadingGeneratorTest extends TestCase
{
    private TextDecoratorFactoryInterface&MockObject $textDecoratorFactory;
    private TextDecoratorInterface&MockObject $headingDecorator;
    private HeadingGenerator $headingGenerator;

    public function setUp(): void
    {
        $this->textDecoratorFactory = $this->getMockBuilder(TextDecoratorFactoryInterface::class)->getMock();
        $this->headingDecorator = $this->getMockBuilder(TextDecoratorInterface::class)->getMock();
        $this->headingGenerator = new HeadingGenerator($this->textDecoratorFactory);
    }

    #[TestDox('generate() method works correctly')]
    public function testGenerate(): void
    {
        $this->textDecoratorFactory->expects(self::once())
            ->method('createHeadingDecorator')
            ->willReturn($this->headingDecorator);

        $this->headingDecorator->expects(self::once())
            ->method('format')
            ->willReturn('');

        $this->headingGenerator->generate('text1', 1, 'dokuwiki');
    }

    #[DataProvider('headingGeneratorTestDataProvider')]
    #[TestDox('generate() method fails on InvalidArgumentException with parameters $text, $level, $format')]
    public function testGenerateFailsOnInvalidArgumentException($text, $level, $format): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->headingGenerator->generate($text, $level, $format);
    }

    public static function headingGeneratorTestDataProvider(): array
    {
        return [
            'testcase 1' => ['', 1, 'dokuwiki'],
            'testcase 2' => ['text1', -1, 'dokuwiki'],
            'testcase 3' => ['text1', 1, '']
        ];
    }
}
