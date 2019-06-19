<?php
namespace Bladerunner;

use Illuminate\Container\Container as BaseContainer;
use Illuminate\Contracts\Container\Container as ContainerContract;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\ViewServiceProvider;

/**
 * Class BladeProvider
 */
class BladeProvider extends ViewServiceProvider
{
    /**
     * @param ContainerContract $container
     * @param array             $config
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct(ContainerContract $container = null, $config = [])
    {
        /** @noinspection PhpParamsInspection */
        parent::__construct($container ?: BaseContainer::getInstance());

        $this->app->bindIf('config', function () use ($config) {
            return $config;
        }, true);
    }

    /**
     * Bind required instances for the service provider.
     */
    public function register()
    {
        $this->registerFilesystem();
        $this->registerEvents();
        $this->registerEngineResolver();
        $this->registerViewFinder();
        $this->registerFactory();
        return $this;
    }

    /**
     * Register Filesystem
     */
    public function registerFilesystem()
    {
        $this->app->bindIf('files', Filesystem::class, true);
        return $this;
    }

    /**
     * Register the events dispatcher
     */
    public function registerEvents()
    {
        $this->app->bindIf('events', Dispatcher::class, true);
        return $this;
    }

    /** @inheritdoc */
    public function registerEngineResolver()
    {
        parent::registerEngineResolver();
        return $this;
    }

    /** @inheritdoc */
    public function registerFactory()
    {
        parent::registerFactory();
        return $this;
    }

    /**
     * Register the view finder implementation.
     */
    public function registerViewFinder()
    {
        $this->app->bindIf('view.finder', function ($app) {
            $config = $this->app['config'];
            $paths = $config['view.paths'];
            $namespaces = $config['view.namespaces'];
            $finder = new FileViewFinder($app['files'], $paths);
            //array_map([$finder, 'addNamespace'], array_keys($namespaces), $namespaces);
            return $finder;
        }, true);
        return $this;
    }

    /**
     * Register the Blade engine implementation.
     *
     * @param  \Illuminate\View\Engines\EngineResolver  $resolver
     * @return void
     */
    public function registerBladeEngine($resolver)
    {
        // The Compiler engine requires an instance of the CompilerInterface, which in
        // this case will be the Blade compiler, so we'll first create the compiler
        // instance to pass into the engine so it can compile the views properly.
        $this->app->singleton('blade.compiler', function () {
            return new BladeCompiler(
                $this->app['files'], $this->app['config']['view.compiled']
            );
        });

        $resolver->register('blade', function () {
            return new CompilerEngine($this->app['blade.compiler']);
        });
    }
}
