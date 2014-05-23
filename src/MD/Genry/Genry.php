<?php
namespace MD\Genry;

use SplFileInfo;

use MD\Foundation\Exceptions\NotFoundException;

use Splot\Framework\Templating\TemplatingEngineInterface;
use Splot\AssetsModule\Assets\AssetsContainer\JavaScriptContainer;
use Splot\AssetsModule\Assets\AssetsContainer\StylesheetContainer;

use MD\Genry\Page;

class Genry
{

    protected $templating;

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
     * Page generation queue.
     * 
     * @var array
     */
    protected $queue = array();

    protected $templatesDir;

    protected $webDir;

    public function __construct(
        TemplatingEngineInterface $templating,
        JavaScriptContainer $javascripts,
        StylesheetContainer $stylesheets,
        $templatesDir,
        $webDir
    ) {
        $this->templating = $templating;
        $this->javascripts = $javascripts;
        $this->stylesheets = $stylesheets;
        $this->templatesDir = $templatesDir;
        $this->webDir = $webDir;
    }

    public function generate($template, array $parameters = array(), $outputFile = null) {
        $page = $this->createPage($template, $parameters, $outputFile);
        return $this->generatePage($page);
    }

    public function generatePage(Page $page) {
        if (!$page->getTemplateFile()->isFile()) {
            throw new NotFoundException('Could not find template to render: '. $page->getTemplateFile()->getPathname());
        }

        $output = $this->templating->render($page->getTemplateName(), $page->getParameters());

        // make sure that the dir the output will be saved, exists
        $outputDir = $page->getOutputFile()->getPath();
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        file_put_contents($page->getOutputFile()->getPathname(), $output);

        $this->javascripts->clearAssets();
        $this->stylesheets->clearAssets();

        return $output;
    }

    public function createPage($template, array $parameters = array(), $outputFile = null) {
        $page = new Page();

        // if absolute path given then take a name from it
        if (mb_substr($template, 0, 1) === DS) {
            $page->setTemplateFile(new SplFileInfo($template));
            $page->setTemplateName($this->templateNameFromPath($template));

        // if relative path given then build template location from the name
        } else {
            $page->setTemplateFile(new SplFileInfo($this->templatesDir . $template));
            $page->setTemplateName($template);
        }

        // if no output file given then build it automatically
        if (!$outputFile) {
            $outputPath = $this->outputPathFromTemplate($page->getTemplateName());
            $page->setOutputFile(new SplFileInfo($outputPath));
            $page->setOutputName($this->outputNameFromPath($outputPath));

        // if absolute path to output file given then take a name from it
        } elseif (mb_substr($outputFile, 0, 1) === DS) {
            $page->setOutputFile(new SplFileInfo($outputFile));
            $page->setOutputName($this->outputNameFromPath($outputFile));

        // if relative path to output given then build output location from the name
        } else {
            $page->setOutputFile(new SplFileInfo($this->webDir . $outputFile));
            $page->setOutputName($outputFile);
        }

        $page->setParameters($parameters);

        return $page;
    }

    public function addToQueue($template, array $parameters = array(), $outputFile = null) {
        $this->queue[] = $this->createPage($template, $parameters, $outputFile);
    }

    public function processQueue(callable $callback = null) {
        while(!empty($this->queue)) {
            $page = array_shift($this->queue);
            $this->generatePage($page);
            
            if ($callback !== null) {
                call_user_func_array($callback, array($page));
            }
        }
    }

    public function getQueue() {
        return $this->queue;
    }

    public function clearQueue() {
        $this->queue = array();
    }

    public function templateNameFromPath($path) {
        return stripos($path, $this->templatesDir) === 0
            ? mb_substr($path, mb_strlen($this->templatesDir))
            : $path;
    }

    public function outputPathFromTemplate($templateName) {
        return $this->webDir . mb_substr($templateName, 0, -5); // remove ".twig" from the end of the file
    }

    public function outputNameFromPath($path) {
        return stripos($path, $this->webDir) === 0
            ? mb_substr($path, mb_strlen($this->webDir))
            : $path;
    }

}