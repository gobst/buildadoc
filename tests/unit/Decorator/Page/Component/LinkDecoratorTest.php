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
declare(strict_types=1);

namespace unit\Decorator\Page\Component;

use Contract\Decorator\DecoratorInterface;
use Decorator\Page\Component\LinkDecorator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(LinkDecorator::class)]
final class LinkDecoratorTest extends TestCase
{
    private DecoratorInterface&MockObject $textDecorator;
    private LinkDecorator $linkDecorator;

    public function setUp(): void
    {
        $this->textDecorator = $this->getMockBuilder(DecoratorInterface::class)->getMock();
        $this->linkDecorator = new LinkDecorator($this->textDecorator);
    }

    #[DataProvider('formatLinkTestDataProvider')]
    #[TestDox('formatLink() method works correctly with parameters $format, $textParts')]
    public function testformatLink(string $format, array $textParts): void
    {
        $this->textDecorator->expects(self::once())
            ->method('formatText')
            ->willReturn('test');

        if(!empty($textParts[1])){
            $this->textDecorator->expects(self::once())
                ->method('getFormat')
                ->with('dokuwiki', 'link_with_text')
                ->willReturn('test');
        }else{
            $this->textDecorator->expects(self::once())
                ->method('getFormat')
                ->with('dokuwiki', 'link_without_text')
                ->willReturn('test');
        }

        $this->linkDecorator->format($format, $textParts);
    }

    #[TestDox('formatLink() method throws InvalidArgumentException on invalid format')]
    public function testformatLinkFailsWithInvalidFormatOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->linkDecorator->format('xyz', []);
    }

    #[TestDox('formatLink() method throws InvalidArgumentException on empty format string')]
    public function testformatLinkFailsWithEmptyFormatStringOnInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->textDecorator->expects(self::once())
            ->method('getFormat')
            ->willReturn('');
        $this->textDecorator->expects(self::never())
            ->method('formatText');

        $this->linkDecorator->format('dokuwiki', ['test']);
    }

    public static function formatLinkTestDataProvider(): array
    {
        return [
            'testcase 1' => ['dokuwiki', ['link']],
            'testcase 2' => ['dokuwiki', ['link', 'linkText']]
        ];
    }
}
