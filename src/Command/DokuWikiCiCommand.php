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

use Exception;
use Service\Class\Data\ClassDataService;
use Service\Class\Documentation\ClassDocumentationService;
use Service\Class\Documentation\Page\ClassPageService;
use Service\File\FileService;
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

        /** @var ClassDataService $classDataService */
        $classDataService = $container->get('Service\Class\Data\ClassDataService');
        /** @var FileService $fileService */
        $fileService = $container->get('Service\File\FileService');
        /** @var ClassPageService $classPageService */
        $classPageService = $container->get('Service\Class\Documentation\Page\ClassPageService');
        $classDocService = new ClassDocumentationService($classDataService, $fileService, $classPageService);

        $classDocService->buildDocumentation($args['source'], $args['destination'], $language, self::FORMAT);

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
