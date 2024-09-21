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

namespace unit\Generator\Documentation\Class\Page\Component\Link;

use Contract\Decorator\Component\Link\LinkFormatterInterface;
use Contract\Decorator\TextDecoratorFactoryInterface;
use Contract\Decorator\TextDecoratorInterface;
use Generator\Documentation\Class\Page\Component\Link\LinkGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

#[CoversClass(LinkGenerator::class)]
class LinkGeneratorTest extends TestCase
{
    private TextDecoratorFactoryInterface&MockObject $textDecoratorFactory;
    private TextDecoratorInterface&MockObject $linkDecorator;
    private LinkGenerator $linkGenerator;

    public function setUp(): void
    {
        $this->textDecoratorFactory = $this->getMockBuilder(TextDecoratorFactoryInterface::class)->getMock();
        $this->linkDecorator = $this->getMockBuilder(TextDecoratorInterface::class)->getMock();
        $this->linkGenerator = new LinkGenerator($this->textDecoratorFactory);
    }

    public function testGenerateWithDokuWikiFormat(): void
    {
        $this->textDecoratorFactory->expects(self::once())
            ->method('createLinkDecorator')
            ->willReturn($this->linkDecorator);

        $this->linkDecorator->expects(self::once())
            ->method('format')
            ->willReturn('');

        $this->linkGenerator->generate('dokuwiki', 'testDest', 'testTxt');
    }

    public function testGenerateWithDokuWikiFormatAndEmptyText(): void
    {
        $this->textDecoratorFactory->expects(self::once())
            ->method('createLinkDecorator')
            ->willReturn($this->linkDecorator);

        $this->linkDecorator->expects(self::once())
            ->method('format')
            ->willReturn('');

        $this->linkGenerator->generate('dokuwiki', 'testDest', '');
    }

    public function testGenerateWillFailOnEmptyFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->textDecoratorFactory->expects(self::never())
            ->method('createLinkDecorator');

        $this->linkDecorator->expects(self::never())
            ->method('format');

        $this->linkGenerator->generate('', 'testDest', 'testTxt');
    }

    public function testGenerateWillFailOnEmptyDestination(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->textDecoratorFactory->expects(self::never())
            ->method('createLinkDecorator');

        $this->linkDecorator->expects(self::never())
            ->method('format');

        $this->linkGenerator->generate('dokuwiki', '', 'testTxt');
    }
}
