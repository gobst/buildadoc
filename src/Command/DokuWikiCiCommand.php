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

namespace Command;

use Contract\Service\Class\Data\ClassDataServiceInterface;
use Contract\Service\Class\Documentation\Page\ClassPageServiceInterface;
use Contract\Service\File\DocFileServiceInterface;
use Contract\Service\File\FileServiceInterface;
use Exception;
use Service\Class\Documentation\ClassDocumentationService;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Webmozart\Assert\Assert;

#[AsCommand(
    name: 'DokuWiki:create-doc',
    description: 'Creates a class documentation for DokuWiki'
)]
class DokuWikiCiCommand extends Command
{
    private const string FORMAT = 'dokuwiki';
    private const string DEFAULT_LANGUAGE = 'de';
    protected static $defaultDescription = 'Creating class documentation for DokuWiki.';

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $args = $input->getArguments();
        $language = empty($args['language']) ? self::DEFAULT_LANGUAGE : $args['language'];

        Assert::stringNotEmpty($args['source']);
        Assert::stringNotEmpty($args['destination']);
        Assert::stringNotEmpty($language);

        $container = new ContainerBuilder();
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../cfg'));
        $loader->load('services.yml');
        $container->compile();

        /** @var ClassDataServiceInterface $classDataService */
        $classDataService = $container->get(ClassDataServiceInterface::class);
        /** @var DocFileServiceInterface $docFileService */
        $docFileService = $container->get(DocFileServiceInterface::class);
        /** @var FileServiceInterface $fileService */
        $fileService = $container->get(FileServiceInterface::class);
        /** @var ClassPageServiceInterface $classPageService */
        $classPageService = $container->get(ClassPageServiceInterface::class);

        $classDocService = new ClassDocumentationService(
            $classDataService,
            $classPageService,
            $docFileService,
            $fileService
        );

        $classDocService->buildDocumentation(
            $args['source'],
            $args['destination'],
            $language,
            self::FORMAT
        );

        $output->writeln('Done!');

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to create a class documentation for DokuWiki.');
        $this->addArgument(
            'source',
            InputArgument::REQUIRED,
            'The source directory for the documentation.'
        )
            ->addArgument(
                'destination',
                InputArgument::REQUIRED,
                'The destination directory for the documentation.'
            )
            ->addArgument(
                'language',
                InputArgument::OPTIONAL,
                'The language that should be used (en or de). Default is "de".'
            );
    }
}
