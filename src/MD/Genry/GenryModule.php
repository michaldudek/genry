<?php
namespace MD\Genry;

use Splot\Framework\Modules\AbstractModule;

use MD\Genry\Genry;
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

}