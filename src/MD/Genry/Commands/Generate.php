<?php
namespace MD\Genry\Commands;

use Splot\Framework\Console\AbstractCommand;

use MD\Genry\Page;

class Generate extends AbstractCommand 
{

    protected static $name = 'generate';
    protected static $description = 'Generate all static pages.';

    public function execute() {
        $this->writeln('Generating...');

        $output = $this;
        $this->get('genry')->generateAll(function(Page $page) use ($output) {
            $output->writeln('Generated <info>'. $page->getOutputName() .'</info> from <comment>'. $page->getTemplateName() .'</comment>...');
        });

        $this->writeln('Done.');
    }

}