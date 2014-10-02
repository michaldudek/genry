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

        $genry = $this->get('genry');
        $genry->setLogger($this->getLogger()); // set genry logger to the console logger to get nice output

        $genry->generateAll();

        $this->writeln('Done.');
    }

}