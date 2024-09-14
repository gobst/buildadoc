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

namespace unit\Generator\Documentation\Class\Page\Component\Constant;

use Contract\Formatter\Component\ListFormatterInterface;
use Dto\Class\Constant;
use Dto\Common\Modifier;
use Generator\Documentation\Class\Page\Component\Constant\ConstantListGenerator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

final class ConstantListGeneratorTest extends TestCase
{
    private ListFormatterInterface&MockObject $listFormatter;
    private ConstantListGenerator $constListGenerator;

    public function setUp(): void
    {
        $this->listFormatter = $this->getMockBuilder(ListFormatterInterface::class)->getMock();
        $this->constListGenerator = new ConstantListGenerator($this->listFormatter);
    }

    public function testGenerateWithDokuWikiFormatAndOrderedList(): void
    {
        $this->listFormatter->expects(self::exactly(6))
            ->method('formatListItem')
            ->willReturn('');

        $this->constListGenerator->generate($this->getTestConstantsData(), 'dokuwiki');
    }

    public function testGenerateWillFailOnEmptyFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->listFormatter->expects(self::never())
            ->method('formatListItem');

        $this->constListGenerator->generate($this->getTestConstantsData(), '');
    }

    public function testGenerateWillFailOnEmptyListType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->listFormatter->expects(self::never())
            ->method('formatListItem');

        $this->constListGenerator->generate($this->getTestConstantsData(), 'dokuwiki', '');
    }

    /**
     * @return Collection<int, Constant>
     */
    private function getTestConstantsData(): Collection
    {
        /** @var Collection<int, Modifier> $pModifiers */
        $pModifiers = Collection::make();
        $pModifiers->push(Modifier::create('public'));

        /** @var Collection<int, Modifier> $prModifiers */
        $prModifiers = Collection::make();
        $prModifiers->push(Modifier::create('private'));

        /** @var Collection<int, Constant> $constants */
        $constants = Collection::make();

        $constantDto = Constant::create('testConst', 'string', 'testval1', $prModifiers);
        $constants->push($constantDto);

        $constantDto = Constant::create('testConst2', 'int', 2, $pModifiers);
        $constants->push($constantDto);

        $constantDto = Constant::create('VAR1', 'string', 'testVal1', $pModifiers);
        $constants->push($constantDto);

        $constantDto = Constant::create('VAR2', 'string', 'testVal2', $pModifiers);
        $constants->push($constantDto);

        $constantDto = Constant::create('VAR1_1', 'string', 'testVal1_1', $pModifiers);
        $constants->push($constantDto);

        $constantDto = Constant::create('VAR2_2', 'string', 'testVal2_2', $pModifiers);
        $constants->push($constantDto);

        return $constants;
    }
}
