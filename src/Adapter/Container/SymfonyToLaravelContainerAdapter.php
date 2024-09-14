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

namespace Adapter\Container;

use Closure;
use Illuminate\Contracts\Container\Container as LaravelContainer;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @SuppressWarnings(PHPMD)
 */
class SymfonyToLaravelContainerAdapter implements LaravelContainer
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function bound($abstract): bool
    {
        return $this->container->has($abstract);
    }

    public function bindMethod($method, $callback): void
    {
        // TODO: Implement bindMethod() method.
    }

    public function singletonIf($abstract, $concrete = null): void
    {
        // TODO: Implement singletonIf() method.
    }

    public function scoped($abstract, $concrete = null): void
    {
        // TODO: Implement scoped() method.
    }

    public function scopedIf($abstract, $concrete = null): void
    {
        // TODO: Implement scopedIf() method.
    }

    public function addContextualBinding($concrete, $abstract, $implementation): void
    {
        // TODO: Implement addContextualBinding() method.
    }

    public function when($concrete): void
    {
        // TODO: Implement when() method.
    }

    public function factory($abstract): void
    {
        // TODO: Implement factory() method.
    }

    public function flush(): void
    {
        // TODO: Implement flush() method.
    }

    public function beforeResolving($abstract, ?Closure $callback = null): void
    {
        // TODO: Implement beforeResolving() method.
    }

    public function get(string $id): void
    {
        // TODO: Implement get() method.
    }

    public function has(string $id): bool
    {
        // TODO: Implement has() method.
        return false;
    }

    public function alias($abstract, $alias): void
    {
        // TODO: Implement alias() method.
    }

    public function tag($abstracts, $tags): void
    {
        // TODO: Implement tag() method.
    }

    public function tagged($tag): void
    {
        // TODO: Implement tagged() method.
    }

    public function bind($abstract, $concrete = null, $shared = false): void
    {
        // TODO: Implement bind() method.
    }

    public function bindIf($abstract, $concrete = null, $shared = false): void
    {
        // TODO: Implement bindIf() method.
    }

    public function singleton($abstract, $concrete = null): void
    {
        // TODO: Implement singleton() method.
    }

    public function extend($abstract, Closure $closure): void
    {
        // TODO: Implement extend() method.
    }

    public function instance($abstract, $instance): void
    {
        // TODO: Implement instance() method.
    }

    public function make($abstract, array $parameters = []): void
    {
        // TODO: Implement make() method.
    }

    public function call($callback, array $parameters = [], $defaultMethod = null): void
    {
        // TODO: Implement call() method.
    }

    public function resolved($abstract): void
    {
        // TODO: Implement resolved() method.
    }

    public function resolving($abstract, ?Closure $callback = null): void
    {
        // TODO: Implement resolving() method.
    }

    public function afterResolving($abstract, ?Closure $callback = null): void
    {
        // TODO: Implement afterResolving() method.
    }
}
