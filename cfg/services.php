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

use Contract\Service\Class\Documentation\Page\TableOfContentsPageServiceInterface;
use Decorator\Page\Component\ClassLinkDestinationDecorator;
use Decorator\Page\Component\HeadingDecorator;
use Decorator\Page\Component\ListDecorator;
use Decorator\Page\Component\MethodLinkDestinationDecorator;
use Generator\Documentation\Class\Page\Component\Class\ClassListGenerator;
use Pipeline\Page\Fetcher\Class\UsedByClassListFetcher;
use Pipeline\Page\Fetcher\TableOfContents\ClassListFetcher;
use Pipeline\Page\Fetcher\TableOfContents\TextFetcher;
use Pipeline\Page\Provider\TableOfContentsPageFetcherProvider;
use Pipeline\Page\TableOfContentsPageMarkerPipeline;
use Service\Class\Documentation\Page\TableOfContentsPageService;
use Service\File\Template\ClassPageTemplateService;
use Service\File\Template\MethodPageTemplateService;
use Service\File\Template\Provider\TemplateServiceProvider;
use Pipeline\Page\Fetcher\Method\MethodSignatureFetcher;
use Pipeline\Page\Fetcher\Method\MethodTableFetcher;
use Pipeline\Page\Fetcher\Method\MethodHeadingFetcher;
use Pipeline\Page\Fetcher\Class\PropertiesListFetcher;
use Pipeline\Page\Fetcher\Class\MethodListFetcher;
use Pipeline\Page\Fetcher\Class\InterfacesListFetcher;
use Pipeline\Page\Fetcher\Class\FilesTableFetcher;
use Pipeline\Page\Fetcher\Class\ConstructorFetcher;
use Pipeline\Page\Fetcher\Class\ConstantListFetcher;
use Pipeline\Page\Fetcher\Class\ClassPathFetcher;
use Pipeline\Page\Fetcher\Class\HeadingFetcher;
use Service\Class\Data\ClassDataService;
use Contract\Service\Class\Data\ClassDataServiceInterface;
use Adapter\Container\SymfonyToLaravelContainerAdapter;
use Service\Class\Documentation\Page\ClassPageService;
use Contract\Service\Class\Documentation\Page\ClassPageServiceInterface;
use Service\File\DocFileService;
use Contract\Service\File\DocFileServiceInterface;
use Service\File\FileService;
use Contract\Service\File\FileServiceInterface;
use Contract\Pipeline\MethodPageMarkerPipelineInterface;
use Pipeline\Page\Provider\MethodPageFetcherProvider;
use Pipeline\Page\MethodPageMarkerPipeline;
use Illuminate\Pipeline\Pipeline;
use Pipeline\Page\Provider\ClassPageFetcherProvider;
use Pipeline\Page\ClassPageMarkerPipeline;
use Service\Class\Filter\ParentClassNameFilter;
use Service\File\Filter\MarkerNameFilter;
use Service\Class\Filter\ModifierFilter;
use Service\Class\Filter\MethodNameFilter;
use Dto\Documentation\DocPage;
use Service\File\Filter\FileNameFilter;
use Service\Class\Filter\TagFilter;
use Service\Class\Filter\PageTitleFilter;
use Service\Class\Filter\ClassNameFilter;
use Dto\Class\ClassDto;
use Dto\Common\File;
use Dto\Method\Method;
use Service\File\Template\TableOfContentsPageTemplateService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->public();

    $services->load('Command\\', '../src/Command/*');
    $services->load('Generator\\', '../src/Generator/*');
    $services->load('Service\\', '../src/Service/*');
    $services->load('Exception\\', '../src/Exception/*');
    $services->load('Dto\\', '../src/Dto/*');
    $services->load('Decorator\\', '../src/Decorator/*');
    $services->load('Contract\\', '../src/Contract/*');
    $services->load('Pipeline\\', '../src/Pipeline/*');

    $services->set(ClassDto::class)
        ->factory([ClassDto::class, 'create'])
        ->args([
            'className',
            'path',
            [],
            [],
        ]);

    $services->set(Method::class)
        ->factory([Method::class, 'create'])
        ->args([
            'methodName',
            [],
            'void',
            'className',
        ]);

    $services->set(DocPage::class)
        ->factory([DocPage::class, 'create'])
        ->args([
            'content',
            'title',
            'file',
            'txt',
        ]);

    $services->set(File::class)
        ->factory([File::class, 'create'])
        ->args([
            'fileName',
            'filePath',
            'basename',
            'directory',
            1,
        ]);

    $services->set(TagFilter::class)
        ->arg('$tag', 'tag');

    $services->set(FileNameFilter::class)
        ->arg('$fileName', new Reference(File::class));

    $services->set(ParentClassNameFilter::class)
        ->arg('$parentClassName', 'classname');

    $services->set(ClassNameFilter::class)
        ->arg('$className', new Reference(ClassDto::class));

    $services->set(PageTitleFilter::class)
        ->arg('$title', new Reference(DocPage::class));

    $services->set(MethodNameFilter::class)
        ->arg('$name', new Reference(Method::class));

    $services->set(ModifierFilter::class)
        ->arg('$modifiers', []);

    $services->set(MarkerNameFilter::class)
        ->arg('$name', 'MARKER');

    $services->set(ClassPageMarkerPipeline::class)
        ->arg('$fetcherProvider', new Reference(ClassPageFetcherProvider::class))
        ->arg('$pipeline', new Reference(Pipeline::class));

    $services->set(MethodPageMarkerPipeline::class)
        ->arg('$fetcherProvider', new Reference(MethodPageFetcherProvider::class))
        ->arg('$pipeline', new Reference(Pipeline::class));

    $services->set(TableOfContentsPageMarkerPipeline::class)
        ->arg('$fetcherProvider', new Reference(TableOfContentsPageFetcherProvider::class))
        ->arg('$pipeline', new Reference(Pipeline::class));

    $services->alias(MethodPageMarkerPipelineInterface::class, MethodPageMarkerPipeline::class);
    $services->alias(FileServiceInterface::class, FileService::class);
    $services->alias(DocFileServiceInterface::class, DocFileService::class);
    $services->alias(ClassPageServiceInterface::class, ClassPageService::class);
    $services->alias(TableOfContentsPageServiceInterface::class, TableOfContentsPageService::class);

    $services->set(SymfonyToLaravelContainerAdapter::class)
        ->arg(0, new Reference('service_container'));

    $services->set(Pipeline::class)
        ->args([new Reference(SymfonyToLaravelContainerAdapter::class)]);

    $services->set('Symfony\Component\Filesystem\Filesystem');

    $services->alias(ClassDataServiceInterface::class, ClassDataService::class);

    $services->set(ClassPageFetcherProvider::class)
        ->arg('$fetchers', [
            'heading' => new Reference(HeadingFetcher::class),
            'classPath' => new Reference(ClassPathFetcher::class),
            'constantList' => new Reference(ConstantListFetcher::class),
            'constructor' => new Reference(ConstructorFetcher::class),
            'filesTable' => new Reference(FilesTableFetcher::class),
            'interfacesList' => new Reference(InterfacesListFetcher::class),
            'methodList' => new Reference(MethodListFetcher::class),
            'propertiesList' => new Reference(PropertiesListFetcher::class),
            'usedByClassesList' => new Reference(UsedByClassListFetcher::class)
        ]);

    $services->set(MethodPageFetcherProvider::class)
        ->arg('$fetchers', [
            'methodHeading' => new Reference(MethodHeadingFetcher::class),
            'methodTable' => new Reference(MethodTableFetcher::class),
            'methodSignature' => new Reference(MethodSignatureFetcher::class),
        ]);

    $services->set(TemplateServiceProvider::class)
        ->arg('$services', [
            'method' => new Reference(MethodPageTemplateService::class),
            'class' => new Reference(ClassPageTemplateService::class),
            'tableofcontents' => new Reference(TableOfContentsPageTemplateService::class),
        ]);

    $services->set(TableOfContentsPageFetcherProvider::class)
        ->arg('$fetchers', [
            'tableofcontentsHeading' => new Reference(\Pipeline\Page\Fetcher\TableOfContents\HeadingFetcher::class),
            'tableofcontentsText' => new Reference(TextFetcher::class),
            'tableofcontentsClassList' => new Reference(ClassListFetcher::class),
        ]);

    $services->set(ClassLinkDestinationDecorator::class)
        ->arg('$mainDirectory', '');

    $services->set(HeadingDecorator::class)
        ->arg('$level', 1);

    $services->set(ListDecorator::class)
        ->arg('$listType', 'method_list');

    $services->set(MethodLinkDestinationDecorator::class)
        ->arg('$mainDirectory', '');
};
