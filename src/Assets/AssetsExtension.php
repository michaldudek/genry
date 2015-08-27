<?php
namespace Genry\Assets;

use Splot\AssetsModule\Twig\Extension\AssetsExtension as BaseAssetsExtension;
use Splot\AssetsModule\Assets\AssetsContainer\JavaScriptContainer;
use Splot\AssetsModule\Assets\AssetsContainer\StylesheetContainer;
use Splot\AssetsModule\Assets\AssetsFinder;

use Genry\Routing\Router;
use Genry\Page;

/**
 * Twig extension for assets.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
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
     * @param Finder $finder Resource finder.
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

    /**
     * Returns the new registered Twig functions.
     *
     * @return array
     */
    public function getFunctions()
    {
        $functions = parent::getFunctions();
        $functions[] = new \Twig_SimpleFunction(
            'asset',
            array($this, 'getAssetRelativeUrl'),
            array('needs_context' => true)
        );
        return $functions;
    }

    /**
     * Generates a relative URL to an asset.
     *
     * @param  array  $context  Page template context.
     * @param  string $resource Original path to the resource.
     *
     * @return string
     */
    public function getAssetRelativeUrl($context, $resource)
    {
        if (!isset($context['_genry_page']) || !$context['_genry_page'] instanceof Page) {
            throw new \RuntimeException(
                'Twig asset() function requires "_genry_page" variable in the template to be set to the current'
                .' rendered page. It must have been overwritten in the context.'
            );
        }

        return $this->router->generateLink($resource, $context['_genry_page']);
    }
}
