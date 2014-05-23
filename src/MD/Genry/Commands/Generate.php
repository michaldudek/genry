<?php
namespace MD\Genry\Commands;

use SplFileInfo;

use MD\Foundation\Exceptions\NotFoundException;
use MD\Foundation\Utils\FilesystemUtils;

use Splot\Framework\Console\AbstractCommand;

use MD\Genry\Page;

class Generate extends AbstractCommand 
{

    protected static $name = 'generate';
    protected static $description = 'Generate all static pages.';

    public function execute() {
        $this->writeln('Generating...');

        $genry = $this->get('genry');
        $templatesDir = $this->container->getParameter('templates_dir');

        $templates = FilesystemUtils::glob($templatesDir .'{,**/}*.html.twig', GLOB_BRACE);

        foreach($templates as $template) {
            // exclude if a partial template, ie. ends with ".inc.html.twig"
            if (preg_match('/\.inc\.html\.twig$/i', $template)) {
                continue;
            }

            $genry->addToQueue($template);
        }

        $output = $this;
        $genry->processQueue(function(Page $page) use ($output, $genry) {
            $output->writeln('Generated <info>'. $page->getOutputName() .'</info> from <comment>'. $page->getTemplateName() .'</comment>...');
        });

        $this->writeln('Done.');
    }

}