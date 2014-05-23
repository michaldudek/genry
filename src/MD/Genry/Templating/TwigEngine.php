<?php
namespace MD\Genry\Templating;

use Twig_Environment;

use Splot\Framework\Templating\TemplatingEngineInterface;
use Splot\AssetsModule\Assets\AssetsContainer\JavaScriptContainer;
use Splot\AssetsModule\Assets\AssetsContainer\StylesheetContainer;

class TwigEngine implements TemplatingEngineInterface
{

    /**
     * Twig engine.
     * 
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * JavaScript container service.
     * 
     * @var JavaScriptContainer
     */
    protected $javascripts;

    /**
     * Stylesheets container service.
     * 
     * @var StylesheetContainer
     */
    protected $stylesheets;

    /**
     * Constructor.
     * 
     * @param Twig_Environment $twig Twig engine.
     */
    public function __construct(
        Twig_Environment $twig,
        JavaScriptContainer $javascripts,
        StylesheetContainer $stylesheets
    ) {
        $this->twig = $twig;
        $this->javascripts = $javascripts;
        $this->stylesheets = $stylesheets;
    }

    /**
     * Renders a view found under the given name with the given variables to be interpolated.
     * 
     * @param string $view View name.
     * @param array $data [optional] Any additional variables to be interpolated in the view template.
     * @return string
     */
    public function render($view, array $data = array()) {
        $output = $this->twig->render($view, $data);

        // inject assets to it
        $output = str_replace($this->javascripts->getPlaceholder(), $this->javascripts->printAssets(), $output);
        $output = str_replace($this->stylesheets->getPlaceholder(), $this->stylesheets->printAssets(), $output);

        return $output;
    }

}