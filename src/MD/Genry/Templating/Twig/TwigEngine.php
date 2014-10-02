<?php
namespace MD\Genry\Templating\Twig;

use Twig_Environment;

use MD\Genry\Templating\TemplatingEngineInterface;

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
     * @param Environment $twig Twig engine that has been overwritten in Genry.
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

    /**
     * Clear any cached templates.
     *
     * This is called before regenerating the whole project to make sure
     * that no templates are cached.
     */
    public function clearCache() {
        $this->twig->clearTemplateCache();
        $this->twig->clearCacheFiles();
    }

}