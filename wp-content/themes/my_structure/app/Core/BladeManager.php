<?php

namespace Core;

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\FileViewFinder;
use Illuminate\View\Factory;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Compilers\BladeCompiler;

class BladeManager
{
    protected $bladeFactory;


    public function __construct($viewPaths, $cachePath)
    {
        $container = $this->initContainer();

        $container->singleton('files', function () {
            return new Filesystem;
        });

        $container->singleton('events', function () {
            return new Dispatcher;
        });

        $container->singleton('view.engine.resolver', function ($container) use ($cachePath) {
            $resolver = new EngineResolver;
            $resolver->register('blade', function () use ($container, $cachePath) {
                $compiler = new BladeCompiler($container['files'], $cachePath);
                return new CompilerEngine($compiler);
            });
            return $resolver;
        });

        if (gettype($viewPaths) === 'string') {
            $viewPaths = [$viewPaths];
        }
        $container->singleton('view.finder', function ($container) use ($viewPaths){
            return new FileViewFinder($container['files'], $viewPaths);
        });

        $container->singleton('view', function ($container) {
            return new Factory(
                $container['view.engine.resolver'],
                $container['view.finder'],
                $container['events']
            );
        });

        $this->registerDirectives($container);

        $this->bladeFactory = $container['view'];

        $this->setGlobalContainer($container);
    }

    protected function initContainer()
    {
        return new Container;
    }

    protected function registerDirectives($container)
    {
        $container['view']->getEngineResolver()->resolve('blade')->getCompiler()->directive('widget', function ($menu_name) {
            return "<?php the_widget('Widget\\MenuWidget', ['menu_name' => {$menu_name}]); ?>";
        });
    }

    public function getBlade()
    {
        return $this->bladeFactory;
    }
    public function render($view, $data = [])
    {
        return $this->bladeFactory->make($view, $data)->render();
    }

    public function setGlobalContainer($container)
    {
        global $globalBladeContainer;
        $globalBladeContainer = $container;
    }
}