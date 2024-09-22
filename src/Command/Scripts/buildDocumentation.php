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

require_once (__DIR__.'/../../../vendor/autoload.php');

use Contract\Service\Class\Data\ClassDataServiceInterface;
use Contract\Service\Class\Documentation\Page\ClassPageServiceInterface;
use Contract\Service\Class\Documentation\Page\TableOfContentsPageServiceInterface;
use Contract\Service\File\DocFileServiceInterface;
use Contract\Service\File\FileServiceInterface;
use Service\Class\Documentation\ClassDocumentationService;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Webmozart\Assert\Assert;

const CFG_DIR = __DIR__ . '/../../../cfg';
const SERVICES_FILE = 'services.php';

$args = [
    'source' => $argv[1],
    'destination' => $argv[2],
    'name' => $argv[3],
    'language' => $argv[4],
    'format' => $argv[5]
];

Assert::stringNotEmpty($args['source']);
Assert::stringNotEmpty($args['destination']);
Assert::stringNotEmpty($args['name']);
Assert::stringNotEmpty($args['language']);
Assert::stringNotEmpty($args['format']);

getDocumentationService()->buildDocumentation(
    $args['source'],
    $args['destination'],
    $args['name'],
    $args['language'],
    $args['format']
);

function getDocumentationService(): ClassDocumentationService
{
    $container = new ContainerBuilder();
    $loader = new PhpFileLoader($container, new FileLocator(CFG_DIR));
    $loader->load(SERVICES_FILE);
    $container->compile();

    /** @var ClassDataServiceInterface $classDataService */
    $classDataService = $container->get(ClassDataServiceInterface::class);
    /** @var DocFileServiceInterface $docFileService */
    $docFileService = $container->get(DocFileServiceInterface::class);
    /** @var FileServiceInterface $fileService */
    $fileService = $container->get(FileServiceInterface::class);
    /** @var ClassPageServiceInterface $classPageService */
    $classPageService = $container->get(ClassPageServiceInterface::class);
    /** @var TableOfContentsPageServiceInterface $tableOfContPageS */
    $tableOfContPageS = $container->get(TableOfContentsPageServiceInterface::class);

    return new ClassDocumentationService(
        $classDataService,
        $classPageService,
        $docFileService,
        $fileService,
        $tableOfContPageS
    );
}
