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

namespace unit\Decorator\Page;

use Decorator\TextDecorator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(TextDecorator::class)]
final class TextDecoratorTest extends TestCase
{
    private TextDecorator $textDecorator;

    public function setUp(): void
    {
        $this->textDecorator = new TextDecorator();
    }

    #[TestDox('formatText() method works correctly with single string replacement')]
    public function testFormatTextWithSingleStringReplacement(): void
    {
        $formatStr = '====== %s ======';
        $contentParts = ['test'];

        $actualString = $this->textDecorator->formatText($formatStr, $contentParts);

        $this->assertSame('====== test ======', $actualString);
    }

    #[TestDox('formatText() method works correctly with multiple string replacement')]
    public function testFormatTextWithMultipleStringReplacement(): void
    {
        $formatStr = '%s %s **$%s**%s';
        $contentParts = ['test', 'test2', 'test3', 'test4'];

        $actualString = $this->textDecorator->formatText($formatStr, $contentParts);

        $this->assertSame('test test2 **$test3**test4', $actualString);
    }

    #[DataProvider('getFormatTestDataProvider')]
    #[TestDox('getFormat() method works correctly with parameters $format, $type')]
    public function testGetFormat(string $format, string $type): void
    {
        $actualString = $this->textDecorator->getFormat($format, $type);

        switch ([$format, $type]) {
            case ['dokuwiki', 'heading_level1']:
                $this->assertSame('====== %s ======', $actualString);
                break;
            case ['dokuwiki', 'heading_level2']:
                $this->assertSame('===== %s =====', $actualString);
                break;
            case ['dokuwiki', 'heading_level3']:
                $this->assertSame('==== %s ====', $actualString);
                break;
            case ['dokuwiki', 'heading_level4']:
                $this->assertSame('=== %s ===', $actualString);
                break;
            case ['dokuwiki', 'heading_level5']:
                $this->assertSame('== %s ==', $actualString);
                break;
            case ['dokuwiki', 'property_list']:
                $this->assertSame('%s %s **$%s**%s', $actualString);
                break;
            case ['dokuwiki', 'constant_list']:
                $this->assertSame('%s %s **%s**%s', $actualString);
                break;
            case ['dokuwiki', 'interface_list']:
            case ['dokuwiki', 'method_list']:
                $this->assertSame('%s', $actualString);
                break;
            case ['dokuwiki', 'usedbyclass_list']:
                $this->assertSame('%s %s', $actualString);
                break;
            case ['dokuwiki', 'link_with_text']:
                $this->assertSame('[[%s|%s]]', $actualString);
                break;
            case ['dokuwiki', 'link_without_text']:
                $this->assertSame('[[%s]]', $actualString);
                break;
            default:
                $this->assertSame('', $actualString);
                break;
        }
    }

    #[DataProvider('getFormatFailsOnInvalidArgumentExceptionTestDataProvider')]
    #[TestDox('getFormat() method throws InvalidArgumentException with parameters $format, $type')]
    public function testGetFormatFailsOnInvalidArgumentException(string $format, string $type): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->textDecorator->getFormat($format, $type);
    }

    public static function getFormatTestDataProvider(): array
    {
        return [
            'testcase 1' => ['dokuwiki', 'heading_level1'],
            'testcase 2' => ['dokuwiki', 'heading_level2'],
            'testcase 3' => ['dokuwiki', 'heading_level3'],
            'testcase 4' => ['dokuwiki', 'heading_level4'],
            'testcase 5' => ['dokuwiki', 'heading_level5'],
            'testcase 6' => ['dokuwiki', 'property_list'],
            'testcase 7' => ['dokuwiki', 'constant_list'],
            'testcase 8' => ['dokuwiki', 'interface_list'],
            'testcase 9' => ['dokuwiki', 'method_list'],
            'testcase 10' => ['dokuwiki', 'usedbyclass_list'],
            'testcase 11' => ['dokuwiki', 'link_with_text'],
            'testcase 12' => ['dokuwiki', 'link_without_text']
        ];
    }

    public static function getFormatFailsOnInvalidArgumentExceptionTestDataProvider(): array
    {
        return [
            'testcase 1' => ['', 'heading_level1'],
            'testcase 2' => ['dokuwiki', ''],
            'testcase 3' => ['', '']
        ];
    }
}
