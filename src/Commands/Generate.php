<?php
namespace Genry\Commands;

use Splot\Framework\Console\AbstractCommand;

use Genry\Page;

/**
 * Generates static pages from all the templates.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
class Generate extends AbstractCommand
{
    /**
     * Command name.
     *
     * @var string
     */
    protected static $name = 'generate';

    /**
     * Command description.
     *
     * @var string
     */
    protected static $description = 'Generate all static pages.';

    /**
     * Executes the command.
     */
    public function execute()
    {
        $this->writeln('Generating...');

        $genry = $this->get('genry');
        $genry->setLogger($this->getLogger()); // set genry logger to the console logger to get nice output

        $genry->generateAll();

        $this->writeln('Done.');
    }
}
