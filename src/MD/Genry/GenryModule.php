<?php
namespace MD\Genry;

use Splot\Framework\Modules\AbstractModule;

use MD\Genry\Genry;
use MD\Genry\Data\Loader;
use MD\Genry\Data\LoaderTwigExtension;
use MD\Genry\Templating\TemplateLoader;
use MD\Genry\Templating\TwigEngine;

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
            return new TwigEngine(
                $c->get('twig'),
                $c->get('javascripts'),
                $c->get('stylesheets')
            );
        });

        $this->container->set('data.loader', function($c) {
            return new Loader($c->getParameter('data_dir'));
        });

        $this->container->set('data.loader.twig_extension', function($c) {
            return new LoaderTwigExtension($c->get('data.loader'));
        });

        $this->container->set('genry', function($c) {
            return new Genry(
                $c->get('templating'),
                $c->get('javascripts'),
                $c->get('stylesheets'),
                $c->getParameter('templates_dir'),
                $c->getParameter('web_dir')
            );
        });
    }

    public function run() {
        parent::run();

        if ($this->container->has('twig')) {
            $this->container->get('twig')->addExtension($this->container->get('data.loader.twig_extension'));
        }
    }

}