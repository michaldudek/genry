<?php
namespace MD\Genry\Templating;

use Twig_Environment;

use Splot\Framework\Templating\TemplatingEngineInterface;

class TwigEngine implements TemplatingEngineInterface
{

    /**
     * Twig engine.
     * 
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * Constructor.
     * 
     * @param Twig_Environment $twig Twig engine.
     */
    public function __construct(Twig_Environment $twig) {
        $this->twig = $twig;
    }

    /**
     * Renders a view found under the given name with the given variables to be interpolated.
     * 
     * @param string $view View name.
     * @param array $data [optional] Any additional variables to be interpolated in the view template.
     * @return string
     */
    public function render($view, array $data = array()) {
        return $this->twig->render($view, $data);
    }

}