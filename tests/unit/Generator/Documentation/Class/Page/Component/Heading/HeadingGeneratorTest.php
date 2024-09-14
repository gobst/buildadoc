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

use Contract\Formatter\Component\HeadingFormatterInterface;
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
    private HeadingFormatterInterface&MockObject $headingFormatter;
    private HeadingGenerator $headingGenerator;

    public function setUp(): void
    {
        $this->headingFormatter = $this->getMockBuilder(HeadingFormatterInterface::class)->getMock();
        $this->headingGenerator = new HeadingGenerator($this->headingFormatter);
    }

    #[TestDox('generate() method works correctly')]
    public function testGenerate(): void
    {
        $this->headingFormatter->expects(self::once())
            ->method('formatHeading')
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
