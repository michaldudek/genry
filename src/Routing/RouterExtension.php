<?php
namespace MD\Genry\Routing;

use Twig_Extension;
use Twig_SimpleFunction;

use MD\Genry\Routing\Router;
use MD\Genry\Page;

class RouterExtension extends Twig_Extension
{

    protected $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    /**
     * Returns Twig functions registered by this extension.
     * 
     * @return array
     */
    public function getFunctions() {
        return array(
            new Twig_SimpleFunction('link', array($this, 'link'), array('needs_context' => true))
        );
    }

    public function link($context, $path) {
        if (!isset($context['_genry_page']) || !$context['_genry_page'] instanceof Page) {
            throw new \RuntimeException('Twig link() function requires "_genry_page" variable in the template to be set to the current rendered page. It must have been overwritten in the context.');
        }

        return $this->router->generateLink($path, $context['_genry_page']);
    }

    /**
     * Returns the name of this extension.
     * 
     * @return string
     */
    public function getName() {
        return 'genry.routing_extension';
    }

}