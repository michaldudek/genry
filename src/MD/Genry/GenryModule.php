<?php
namespace MD\Genry;

use Splot\Framework\Modules\AbstractModule;

use MD\Genry\Genry;
use MD\Genry\Assets\AssetsExtension;
use MD\Genry\Assets\AssetsInjector;
use MD\Genry\Data\Loader;
use MD\Genry\Data\LoaderTwigExtension;
use MD\Genry\Events\PageRendered;
use MD\Genry\TemplatesWatcher;
use MD\Genry\Markdown\Markdown;
use MD\Genry\Markdown\MarkdownTwigExtension;
use MD\Genry\Routing\Router;
use MD\Genry\Routing\RouterExtension;
use MD\Genry\Templating\Twig\TemplateLoader;
use MD\Genry\Templating\Twig\TwigEngine;

class GenryModule extends AbstractModule
{

    public function configure() {
        parent::configure();

        // overwrite template loader
        $this->container->set('twig.template_loader', function($c) {
            return new TemplateLoader($c->getParameter('templates_dir'));
        });

        // overwrite templating service
        $this->container->set('templating', function($c) {
            return new TwigEngine($c->get('twig'));
        });

        // overwrite assets twig extension
        $this->container->set('assets.twig_extension', function($c) {
            return new AssetsExtension(
                $c->get('assets.finder'),
                $c->get('assets.javascripts'),
                $c->get('assets.stylesheets'),
                $c->get('genry.router')
            );
        });

        $this->container->set('genry.assets_injector', function($c) {
            return new AssetsInjector(
                $c->get('javascripts'),
                $c->get('stylesheets'),
                $c->get('genry.router')
            );
        });

        $this->container->set('genry.watcher.templates', function($c) {
            return new TemplatesWatcher($c->getParameter('templates_dir'));
        });

        $this->container->set('data.loader', function($c) {
            return new Loader($c->getParameter('data_dir'));
        });

        $this->container->set('data.loader.twig_extension', function($c) {
            return new LoaderTwigExtension($c->get('data.loader'));
        });

        $this->container->set('markdown', function($c) {
            return new Markdown();
        });

        $this->container->set('markdown.twig_extension', function($c) {
            return new MarkdownTwigExtension($c->get('markdown'), $c->getParameter('templates_dir'));
        });

        $this->container->set('genry', function($c) {
            $genry = new Genry(
                $c->get('templating'),
                $c->get('event_manager'),
                $c->getParameter('templates_dir'),
                $c->getParameter('web_dir')
            );
            $genry->addFileWatcher($c->get('genry.watcher.templates'));
            return $genry;
        });

        $this->container->set('genry.router', function($c) {
            return new Router($c->getParameter('web_dir'));
        });

        $this->container->set('genry.router_extension', function($c) {
            return new RouterExtension($c->get('genry.router'));
        });
    }

    public function run() {
        parent::run();

        $container = $this->container;

        if ($container->has('twig')) {
            $twig = $container->get('twig');
            $twig->addExtension($container->get('data.loader.twig_extension'));
            $twig->addExtension($container->get('markdown.twig_extension'));
            $twig->addExtension($container->get('genry.router_extension'));
        }

        $container->get('event_manager')->subscribe(PageRendered::getName(), function($event) use ($container) {
            $container->get('genry.assets_injector')->onPageRendered($event);
        });
    }

}