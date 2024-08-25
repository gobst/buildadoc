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

class SymfonyToLaravelContainerAdapter implements LaravelContainer
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function bound($abstract)
    {
        return $this->container->has($abstract);
    }

    public function bindMethod($method, $callback)
    {
        // TODO: Implement bindMethod() method.
    }

    public function singletonIf($abstract, $concrete = null)
    {
        // TODO: Implement singletonIf() method.
    }

    public function scoped($abstract, $concrete = null)
    {
        // TODO: Implement scoped() method.
    }

    public function scopedIf($abstract, $concrete = null)
    {
        // TODO: Implement scopedIf() method.
    }

    public function addContextualBinding($concrete, $abstract, $implementation)
    {
        // TODO: Implement addContextualBinding() method.
    }

    public function when($concrete)
    {
        // TODO: Implement when() method.
    }

    public function factory($abstract)
    {
        // TODO: Implement factory() method.
    }

    public function flush()
    {
        // TODO: Implement flush() method.
    }

    public function beforeResolving($abstract, ?Closure $callback = null)
    {
        // TODO: Implement beforeResolving() method.
    }

    public function get(string $id)
    {
        // TODO: Implement get() method.
    }

    public function has(string $id): bool
    {
        // TODO: Implement has() method.
    }

    public function alias($abstract, $alias)
    {
        // TODO: Implement alias() method.
    }

    public function tag($abstracts, $tags)
    {
        // TODO: Implement tag() method.
    }

    public function tagged($tag)
    {
        // TODO: Implement tagged() method.
    }

    public function bind($abstract, $concrete = null, $shared = false)
    {
        // TODO: Implement bind() method.
    }

    public function bindIf($abstract, $concrete = null, $shared = false)
    {
        // TODO: Implement bindIf() method.
    }

    public function singleton($abstract, $concrete = null)
    {
        // TODO: Implement singleton() method.
    }

    public function extend($abstract, Closure $closure)
    {
        // TODO: Implement extend() method.
    }

    public function instance($abstract, $instance)
    {
        // TODO: Implement instance() method.
    }

    public function make($abstract, array $parameters = [])
    {
        // TODO: Implement make() method.
    }

    public function call($callback, array $parameters = [], $defaultMethod = null)
    {
        // TODO: Implement call() method.
    }

    public function resolved($abstract)
    {
        // TODO: Implement resolved() method.
    }

    public function resolving($abstract, ?Closure $callback = null)
    {
        // TODO: Implement resolving() method.
    }

    public function afterResolving($abstract, ?Closure $callback = null)
    {
        // TODO: Implement afterResolving() method.
    }
}
