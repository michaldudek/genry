<?php
namespace Genry\Assets;

use Splot\AssetsModule\Assets\Asset;
use Splot\AssetsModule\Assets\AssetsContainer;
use Splot\AssetsModule\Assets\AssetsContainer\JavaScriptContainer;
use Splot\AssetsModule\Assets\AssetsContainer\StylesheetContainer;

use Genry\Events\PageRendered;
use Genry\Routing\Router;
use Genry\Page;

class AssetsInjector
{

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

    protected $router;

    public function __construct(
        JavaScriptContainer $javascripts,
        StylesheetContainer $stylesheets,
        Router $router
    ) {
        $this->javascripts = $javascripts;
        $this->stylesheets = $stylesheets;
        $this->router = $router;
    }

    public function onPageRendered(PageRendered $event) {
        $page = $event->getPage();
        $output = $event->getOutput();

        // make URL's to all assets relative so that they can work locally and in a subdir
        $this->makeRelativeUrls($this->javascripts, $page);
        $this->makeRelativeUrls($this->stylesheets, $page);

        // inject assets to it
        $output = str_replace($this->javascripts->getPlaceholder(), $this->javascripts->printAssets(), $output);
        $output = str_replace($this->stylesheets->getPlaceholder(), $this->stylesheets->printAssets(), $output);

        // clear assets now, for this page
        $this->javascripts->clearAssets();
        $this->stylesheets->clearAssets();

        $event->setOutput($output);
    }

    protected function makeRelativeUrls(AssetsContainer $container, Page $relativeTo) {
        foreach($container->getAssets() as $asset) {
            $this->makeRelativeUrl($asset, $relativeTo);
        }
    }

    protected function makeRelativeUrl(Asset $asset, Page $relativeTo) {
        $url = $asset->getUrl();

        // omit external url's
        if (stripos($url, 'http://') === 0 || stripos($url, 'https://') === 0 || stripos($url, '//') === 0) {
            return true;
        }

        $url = $this->router->generateLink($url, $relativeTo);
        $asset->setUrl($url);
    }

}