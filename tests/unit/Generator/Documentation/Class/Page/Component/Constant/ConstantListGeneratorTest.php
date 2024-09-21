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

use Contract\Decorator\TextDecoratorFactoryInterface;
use Contract\Decorator\TextDecoratorInterface;
use Contract\Service\Class\Data\ModifierDataServiceInterface;
use Dto\Class\Constant;
use Dto\Common\Modifier;
use Generator\Documentation\Class\Page\Component\Constant\ConstantListGenerator;
use Illuminate\Support\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Webmozart\Assert\InvalidArgumentException;

final class ConstantListGeneratorTest extends TestCase
{
    private TextDecoratorInterface&MockObject $listDecorator;
    private TextDecoratorFactoryInterface&MockObject $textDecoratorFactory;
    private ModifierDataServiceInterface&MockObject $modifierDataService;
    private ConstantListGenerator $constListGenerator;

    public function setUp(): void
    {
        $this->textDecoratorFactory = $this->getMockBuilder(TextDecoratorFactoryInterface::class)->getMock();
        $this->listDecorator = $this->getMockBuilder(TextDecoratorInterface::class)->getMock();
        $this->modifierDataService = $this->getMockBuilder(ModifierDataServiceInterface::class)->getMock();

        $this->constListGenerator = new ConstantListGenerator($this->textDecoratorFactory, $this->modifierDataService);
    }

    public function testGenerateWithDokuWikiFormatAndOrderedList(): void
    {
        $this->textDecoratorFactory->expects($this->once())
            ->method('createListDecorator')
            ->willReturn($this->listDecorator);

        $this->listDecorator->expects(self::exactly(6))
            ->method('format')
            ->willReturn('');

        $this->modifierDataService->expects(self::exactly(6))
            ->method('implodeModifierDTOCollection')
            ->willReturn('public');

        $this->constListGenerator->generate($this->getTestConstantsData(), 'dokuwiki');
    }

    public function testGenerateWillFailOnEmptyFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->textDecoratorFactory->expects(self::never())
            ->method('createListDecorator');

        $this->listDecorator->expects(self::never())
            ->method('format');

        $this->modifierDataService->expects(self::never())
            ->method('implodeModifierDTOCollection');

        $this->constListGenerator->generate($this->getTestConstantsData(), '');
    }

    public function testGenerateWillFailOnEmptyListType(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->textDecoratorFactory->expects(self::never())
            ->method('createListDecorator');

        $this->listDecorator->expects(self::never())
            ->method('format');

        $this->modifierDataService->expects(self::never())
            ->method('implodeModifierDTOCollection');

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
