<?php

namespace Bladerunner;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\CompilerEngine;
//use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

class Blade
{
    /**
     * Array containing paths where to look for blade files.
     *
     * @var array
     */
    public $viewPaths;

    /**
     * Location where to store cached views.
     *
     * @var string
     */
    public $cachePath;

    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * @var \Illuminate\View\Factory
     */
    protected $instance;

    /**
     * Initialize class.
     *
     * @param array                         $viewPaths
     * @param string                        $cachePath
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function __construct($viewPaths = [], $cachePath, Dispatcher $events = null)
    {
        $this->container = new Container();

        $this->viewPaths = (array) $viewPaths;

        $this->cachePath = $cachePath;

        $this->registerFilesystem();

        $this->registerEvents($events ?: new Dispatcher());

        $this->registerEngineResolver();

        $this->registerViewFinder();

        $this->instance = $this->registerFactory();
    }

    /**
     * Get the view instance.
     *
     * @return \Illuminate\View\Factory|void
     */
    public function view()
    {
        return $this->instance;
    }

    /**
     * Register filesystem.
     */
    public function registerFilesystem()
    {
        $this->container->singleton('files', function () {
            return new Filesystem();
        });
    }

    /**
     * Register events.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function registerEvents(Dispatcher $events)
    {
        $this->container->singleton('events', function () use ($events) {
            return $events;
        });
    }

    /**
     * Register the engine resolver instance.
     *
     * @return void
     */
    public function registerEngineResolver()
    {
        $me = $this;

        $this->container->singleton('view.engine.resolver', function ($app) use ($me) {
            $resolver = new EngineResolver();

            // Next we will register the various engines with the resolver so that the
            // environment can resolve the engines it needs for various views based
            // on the extension of view files. We call a method for each engines.
            foreach (['php', 'blade'] as $engine) {
                $me->{'register'.ucfirst($engine).'Engine'}($resolver);
            }

            return $resolver;
        });
    }

    /**
     * Register the PHP engine implementation.
     *
     * @param \Illuminate\View\Engines\EngineResolver $resolver
     *
     * @return void
     */
    public function registerPhpEngine($resolver)
    {
        $resolver->register('php', function () { return new PhpEngine(); });
    }

    /**
     * Register the Blade engine implementation.
     *
     * @param \Illuminate\View\Engines\EngineResolver $resolver
     *
     * @return void
     */
    public function registerBladeEngine($resolver)
    {
        $me = $this;
        $app = $this->container;

        // The Compiler engine requires an instance of the CompilerInterface, which in
        // this case will be the Blade compiler, so we'll first create the compiler
        // instance to pass into the engine so it can compile the views properly.
        $this->container->singleton('blade.compiler', function ($app) use ($me) {

            $cache = $me->cachePath;
            $compiler = new WPCompiler($app['files'], $cache);

            $extensions = CompilerExtensions::getAllExtensions();
            if ($extensions && is_array($extensions)) {
                foreach ($extensions as $extension) {
                    $compiler->extend(function ($value) use ($extension) {
                        return preg_replace($extension->pattern, $extension->replace, $value);
                    });
                }
            }

            return $compiler;
        });

        $resolver->register('blade', function () use ($app) {
            return new CompilerEngine($app['blade.compiler'], $app['files']);
        });
    }

    /**
     * Register the view finder implementation.
     *
     * @return void
     */
    public function registerViewFinder()
    {
        $me = $this;
        $this->container->singleton('view.finder', function ($app) use ($me) {
            $paths = $me->viewPaths;

            return new FileViewFinder($app['files'], $paths);
        });
    }

    /**
     * Register the view environment.
     *
     * @return void
     */
    public function registerFactory()
    {
        // Next we need to grab the engine resolver instance that will be used by the
        // environment. The resolver will be used by an environment to get each of
        // the various engine implementations such as plain PHP or Blade engine.
        $resolver = $this->container['view.engine.resolver'];

        $finder = $this->container['view.finder'];

        $env = new Factory($resolver, $finder, $this->container['events']);

        // We will also set the container instance on this view environment since the
        // view composers may be classes registered in the container, which allows
        // for great testable, flexible composers for the application developer.
        $env->setContainer($this->container);

        return $env;
    }

    /**
     * Get the Blade compiler.
     *
     * @return \Illuminate\View\Compilers\BladeCompiler
     */
    public function getCompiler()
    {
        return $this->container['blade.compiler'];
    }
}
