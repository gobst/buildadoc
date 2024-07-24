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

namespace unit\Generator\Documentation\Class\Page\Component\Interface;

use Collection\InterfaceCollection;
use Contract\Formatter\Component\ListFormatterInterface;
use Contract\Generator\Documentation\Class\Page\Component\Link\LinkGeneratorInterface;
use Dto\Class\InterfaceDto;
use Generator\Documentation\Class\Page\Component\Interface\InterfaceListGenerator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

final class InterfacesListGeneratorTest extends TestCase
{
    private ListFormatterInterface&MockObject $listFormatter;
    private LinkGeneratorInterface&MockObject $linkGenerator;
    private InterfaceListGenerator $interListGenerator;

    public function setUp(): void
    {
        $this->listFormatter = $this->getMockBuilder(ListFormatterInterface::class)->getMock();
        $this->linkGenerator = $this->getMockBuilder(LinkGeneratorInterface::class)->getMock();
        $this->interListGenerator = new InterfaceListGenerator($this->linkGenerator, $this->listFormatter);
    }

    public function testGenerateWithDokuWikiFormatAndOrderedList(): void
    {
        $this->linkGenerator->expects(self::exactly(2))
            ->method('generate')
            ->willReturn('');
        $this->listFormatter->expects(self::exactly(2))
            ->method('formatListItem')
            ->willReturn('');

        $this->interListGenerator->generate($this->getTestInterfaceData(), 'dokuwiki');
    }

    public function testGenerateWithDokuWikiFormatAndUnorderedList(): void
    {
        $this->linkGenerator->expects(self::exactly(2))
            ->method('generate')
            ->willReturn('');
        $this->listFormatter->expects(self::exactly(2))
            ->method('formatListItem')
            ->willReturn('');

        $this->interListGenerator->generate($this->getTestInterfaceData(), 'dokuwiki', 'unordered');
    }

    public function testGenerateWillFailOnEmptyFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->linkGenerator->expects(self::never())
            ->method('generate')
            ->willReturn('');
        $this->listFormatter->expects(self::never())
            ->method('formatListItem');

        $this->interListGenerator->generate($this->getTestInterfaceData(), '');
    }

    public function testGenerateWillFailOnEmptyListType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->linkGenerator->expects(self::never())
            ->method('generate')
            ->willReturn('');
        $this->listFormatter->expects(self::never())
            ->method('formatListItem');

        $this->interListGenerator->generate($this->getTestInterfaceData(), 'dokuwiki', '');
    }

    /**
     * @return Collection<int, InterfaceDto>
     */
    private function getTestInterfaceData(): Collection
    {
        $interfaces = Collection::make();

        $interfaceDto = InterfaceDto::create('testInterface');
        $interfaces->push($interfaceDto);

        $interfaceDto = InterfaceDto::create('testInterface2');
        $interfaces->push($interfaceDto);

        return $interfaces;
    }
}
