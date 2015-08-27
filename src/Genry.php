<?php
namespace Genry;

use SplFileInfo;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\NullLogger;

use MD\Foundation\Exceptions\NotFoundException;
use MD\Foundation\Utils\FilesystemUtils;

use Splot\EventManager\EventManager;

use Genry\Events\DidGenerate;
use Genry\Events\PageRendered;
use Genry\Events\WillGenerate;
use Genry\FileWatcher\FileWatcherInterface;
use Genry\Templating\TemplatingEngineInterface;
use Genry\Page;

class Genry implements LoggerAwareInterface
{

    protected $templating;

    /**
     * Splot event manager.
     * 
     * @var EventManager
     */
    protected $eventManager;

    protected $templatesDir;

    protected $webDir;

    protected $fileWatchers = array();

    /**
     * Page generation queue.
     * 
     * @var array
     */
    protected $queue = array();

    /**
     * Logger.
     * 
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        TemplatingEngineInterface $templating,
        EventManager $eventManager,
        $templatesDir,
        $webDir,
        LoggerInterface $logger = null
    ) {
        $this->templating = $templating;
        $this->eventManager = $eventManager;
        $this->templatesDir = $templatesDir;
        $this->webDir = $webDir;
        $this->logger = $logger ? $logger : new NullLogger();
    }

    public function generateAll() {
        // force clearing cache before every generation
        $this->templating->clearCache();

        $this->eventManager->trigger(new WillGenerate());

        $templates = FilesystemUtils::glob($this->templatesDir .'{,**/}*.html.twig', GLOB_BRACE);

        foreach($templates as $template) {
            // exclude if a partial template, ie. ends with ".inc.html.twig"
            if (preg_match('/\.inc\.html\.twig$/i', $template)) {
                continue;
            }

            $this->addToQueue($template);
        }

        $this->processQueue();

        $this->eventManager->trigger(new DidGenerate());

        return true;
    }

    public function generate($template, array $parameters = array(), $outputFile = null) {
        $page = $this->createPage($template, $parameters, $outputFile);
        return $this->generatePage($page);
    }

    public function generatePage(Page $page) {
        if (!$page->getTemplateFile()->isFile()) {
            throw new NotFoundException('Could not find template to render: '. $page->getTemplateFile()->getPathname());
        }

        $output = $this->templating->render($page->getTemplateName(), array_merge($page->getParameters(), array(
            '_genry_page' => $page
        )));

        // trigger event
        $event = new PageRendered($page, $output);
        $this->eventManager->trigger($event);
        // update the final output with the output from the event
        $output = $event->getOutput();

        // make sure that the dir the output will be saved, exists
        $outputDir = $page->getOutputFile()->getPath();
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        file_put_contents($page->getOutputFile()->getPathname(), $output);

        $this->logger->debug('Generated {output} from {template}', array(
            'output' => $page->getOutputName(),
            'template' => $page->getTemplateName()
        ));

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

    public function watch() {
        $lastModifications = array();
        $firstRun = true;

        while(true) {
            // gather files from watchers on every check in case new files have appeared
            $files = array();
            foreach($this->fileWatchers as $watcher) {
                $files = array_merge($files, $watcher->filesToWatch());
            }

            $modified = false;
            foreach($files as $file) {
                $modificationTime = filemtime($file);

                // if a new file or a file that has been modified since last check
                // then mark that project was modified
                if (!$firstRun && (!isset($lastModifications[$file]) || $modificationTime > $lastModifications[$file])) {
                    $modified = true;
                    $this->logger->info(NL . 'Registered modification of {file}...', array(
                        'file' => $file
                    ));
                }

                $lastModifications[$file] = filemtime($file);
            }

            // if project has been modified then regenerate it
            if ($modified) {
                $this->generateAll();
            }

            $firstRun = false;
            usleep(1000000); // sleep for 1s before checking again
        }

        return false;
    }

    public function addToQueue($template, array $parameters = array(), $outputFile = null) {
        $this->queue[] = $this->createPage($template, $parameters, $outputFile);
    }

    public function processQueue() {
        while(!empty($this->queue)) {
            $page = array_shift($this->queue);
            $this->generatePage($page);
        }
    }

    public function getQueue() {
        return $this->queue;
    }

    public function clearQueue() {
        $this->queue = array();
    }

    public function addFileWatcher(FileWatcherInterface $watcher) {
        $this->fileWatchers[] = $watcher;
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

    public function setLogger(LoggerInterface $logger) {
        $this->logger = $logger;
    }

}