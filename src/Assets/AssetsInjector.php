<?php
namespace Genry\Assets;

use Splot\AssetsModule\Assets\Asset;
use Splot\AssetsModule\Assets\AssetsContainer;
use Splot\AssetsModule\Assets\AssetsContainer\JavaScriptContainer;
use Splot\AssetsModule\Assets\AssetsContainer\StylesheetContainer;

use Genry\Events\PageRendered;
use Genry\Routing\Router;
use Genry\Page;

/**
 * Injects assets links to templates dynamically.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
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

    /**
     * Genry router.
     *
     * @var Router
     */
    protected $router;

    /**
     * Constructor.
     *
     * @param JavaScriptContainer $javascripts JavaScript container.
     * @param StylesheetContainer $stylesheets CSS container.
     * @param Router              $router      Genry router.
     */
    public function __construct(
        JavaScriptContainer $javascripts,
        StylesheetContainer $stylesheets,
        Router $router
    ) {
        $this->javascripts = $javascripts;
        $this->stylesheets = $stylesheets;
        $this->router = $router;
    }

    /**
     * Event listener for `event.page_rendered` event.
     *
     * @param  PageRendered $event The event.
     */
    public function onPageRendered(PageRendered $event)
    {
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

    /**
     * Converts URL's to assets in the passed assed container relative to the given page.
     *
     * @param  AssetsContainer $container  An assets container.
     * @param  Page            $relativeTo Page object to which the URL should be relative.
     */
    protected function makeRelativeUrls(AssetsContainer $container, Page $relativeTo)
    {
        foreach ($container->getAssets() as $asset) {
            $this->makeRelativeUrl($asset, $relativeTo);
        }
    }

    /**
     * Makes an asset's URL relative to the given page.
     *
     * @param  Asset  $asset      Asset to be made relative.
     * @param  Page   $relativeTo The "owning" page.
     */
    protected function makeRelativeUrl(Asset $asset, Page $relativeTo)
    {
        $url = $asset->getUrl();

        // omit external url's
        if (stripos($url, 'http://') === 0 || stripos($url, 'https://') === 0 || stripos($url, '//') === 0) {
            return true;
        }

        $url = $this->router->generateLink($url, $relativeTo);
        $asset->setUrl($url);
    }
}
