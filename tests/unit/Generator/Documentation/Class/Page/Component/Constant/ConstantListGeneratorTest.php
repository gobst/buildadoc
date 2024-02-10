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

use Collection\ConstantCollection;
use Collection\ModifierCollection;
use Contract\Formatter\Component\ListFormatterInterface;
use Dto\Class\Constant;
use Dto\Common\Modifier;
use Generator\Documentation\Class\Page\Component\Constant\ConstantListGenerator;
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

    private function getTestConstantsData(): ConstantCollection
    {
        $pModifiers = new ModifierCollection();
        $pModifiers->add(Modifier::create('public'));

        $prModifiers = new ModifierCollection();
        $prModifiers->add(Modifier::create('private'));

        $constants = new ConstantCollection();

        $constantDto = Constant::create('testConst', 'string', 'testval1', $prModifiers);
        $constants->add($constantDto);

        $constantDto = Constant::create('testConst2', 'int', 2, $pModifiers);
        $constants->add($constantDto);

        $constantDto = Constant::create('VAR1', 'string', 'testVal1', $pModifiers);
        $constants->add($constantDto);

        $constantDto = Constant::create('VAR2', 'string', 'testVal2', $pModifiers);
        $constants->add($constantDto);

        $constantDto = Constant::create('VAR1_1', 'string', 'testVal1_1', $pModifiers);
        $constants->add($constantDto);

        $constantDto = Constant::create('VAR2_2', 'string', 'testVal2_2', $pModifiers);
        $constants->add($constantDto);

        return $constants;
    }
}
