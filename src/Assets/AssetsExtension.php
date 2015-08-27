<?php
namespace Genry\Assets;

use Splot\AssetsModule\Twig\Extension\AssetsExtension as BaseAssetsExtension;
use Splot\AssetsModule\Assets\AssetsContainer\JavaScriptContainer;
use Splot\AssetsModule\Assets\AssetsContainer\StylesheetContainer;
use Splot\AssetsModule\Assets\AssetsFinder;

use Genry\Routing\Router;
use Genry\Page;

class AssetsExtension extends BaseAssetsExtension
{

    /**
     * Genry router.
     *
     * @var Router
     */
    protected $router;

    /**
     * Constructor.
     *
     * @param Finder $resourceFinder Resource finder.
     * @param JavaScriptContainer $javascripts JavaScript container service.
     * @param StylesheetContainer $stylesheets Stylesheets container service.
     * @param Router $router Genry router.
     */
    public function __construct(
        AssetsFinder $finder,
        JavaScriptContainer $javascripts,
        StylesheetContainer $stylesheets,
        Router $router
    ) {
        parent::__construct($finder, $javascripts, $stylesheets);
        $this->router = $router;
    }

    public function getFunctions()
    {
        $functions = parent::getFunctions();
        $functions[] = new \Twig_SimpleFunction('asset', array($this, 'getAssetRelativeUrl'), array('needs_context' => true));
        return $functions;
    }

    public function getAssetRelativeUrl($context, $resource)
    {
        if (!isset($context['_genry_page']) || !$context['_genry_page'] instanceof Page) {
            throw new \RuntimeException('Twig asset() function requires "_genry_page" variable in the template to be set to the current rendered page. It must have been overwritten in the context.');
        }

        $url = $this->getAssetUrl($resource);
        return $this->router->generateLink($resource, $context['_genry_page']);
    }
}
