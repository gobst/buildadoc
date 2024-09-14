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

use Contract\Formatter\Component\Link\LinkFormatterInterface;
use Generator\Documentation\Class\Page\Component\Link\LinkGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

class LinkGeneratorTest extends TestCase
{
    private LinkFormatterInterface&MockObject $linkFormatter;
    private LinkGenerator $linkGenerator;

    public function setUp(): void
    {
        $this->linkFormatter = $this->getMockBuilder(LinkFormatterInterface::class)->getMock();
        $this->linkGenerator = new LinkGenerator($this->linkFormatter);
    }

    public function testGenerateWithDokuWikiFormat(): void
    {
        $this->linkFormatter->expects(self::once())
            ->method('formatLink')
            ->willReturn('');

        $this->linkGenerator->generate('dokuwiki', 'testDest', 'testTxt');
    }

    public function testGenerateWithDokuWikiFormatAndEmptyText(): void
    {
        $this->linkFormatter->expects(self::once())
            ->method('formatLink')
            ->willReturn('');

        $this->linkGenerator->generate('dokuwiki', 'testDest', '');
    }

    public function testGenerateWillFailOnEmptyFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->linkFormatter->expects(self::never())
            ->method('formatLink');

        $this->linkGenerator->generate('', 'testDest', 'testTxt');
    }

    public function testGenerateWillFailOnEmptyDestination(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->linkFormatter->expects(self::never())
            ->method('formatLink');

        $this->linkGenerator->generate('dokuwiki', '', 'testTxt');
    }
}
