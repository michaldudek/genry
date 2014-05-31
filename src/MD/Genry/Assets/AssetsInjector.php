<?php
namespace MD\Genry\Assets;

use Splot\AssetsModule\Assets\Asset;
use Splot\AssetsModule\Assets\AssetsContainer;
use Splot\AssetsModule\Assets\AssetsContainer\JavaScriptContainer;
use Splot\AssetsModule\Assets\AssetsContainer\StylesheetContainer;

use MD\Genry\Events\PageRendered;
use MD\Genry\Page;

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

    protected $webDir;

    public function __construct(
        JavaScriptContainer $javascripts,
        StylesheetContainer $stylesheets,
        $webDir
    ) {
        $this->javascripts = $javascripts;
        $this->stylesheets = $stylesheets;
        $this->webDir = $webDir;
    }

    public function onPageRendered(PageRendered $event) {
        $page = $event->getPage();
        $output = $event->getOutput();

        // make URL's to all assets relative so that they can work locally and in a subdir
        $this->makeRelativeUrls($this->javascripts, $page->getOutputFile()->getPath());
        $this->makeRelativeUrls($this->stylesheets, $page->getOutputFile()->getPath());

        // inject assets to it
        $output = str_replace($this->javascripts->getPlaceholder(), $this->javascripts->printAssets(), $output);
        $output = str_replace($this->stylesheets->getPlaceholder(), $this->stylesheets->printAssets(), $output);

        // clear assets now, for this page
        $this->javascripts->clearAssets();
        $this->stylesheets->clearAssets();

        $event->setOutput($output);
    }

    protected function makeRelativeUrls(AssetsContainer $container, $relativeTo) {
        foreach($container->getAssets() as $asset) {
            $this->makeRelativeUrl($asset, $relativeTo);
        }
    }

    protected function makeRelativeUrl(Asset $asset, $relativeTo) {
        $url = $asset->getUrl();

        // omit external url's
        if (stripos($url, 'http://') === 0 || stripos($url, 'https://') === 0 || stripos($url, '//') === 0) {
            return true;
        }

        $relativeTo = rtrim($relativeTo, DS) . DS;
        if (stripos($relativeTo, $this->webDir) !== 0) {
            throw new \RuntimeException('The rendered page is not inside the web dir!');
        }

        $relativeTo = mb_substr($relativeTo, mb_strlen($this->webDir));
        $url = ltrim($url, '/');

        $relative = '';
        foreach(explode('/', $relativeTo) as $dir) {
            $relative .= !empty($dir) ? '../' : '';
        }

        $url = $relative . $url;
        $asset->setUrl($url);
    }

}