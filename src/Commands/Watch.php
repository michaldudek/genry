<?php
namespace Genry\Commands;

use MD\Foundation\Utils\FilesystemUtils;

use Splot\Framework\Console\AbstractCommand;

use Genry\Page;

/**
 * Watches files.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
class Watch extends AbstractCommand
{

    /**
     * Command name.
     *
     * @var string
     */
    protected static $name = 'watch';

    /**
     * Command description.
     *
     * @var string
     */
    protected static $description = 'Watch the templates folder for any changes.';

    /**
     * Executes the command.
     */
    public function execute()
    {
        $this->writeln('Watching the project for changes...');

        $genry = $this->get('genry');
        $genry->setLogger($this->getLogger()); // set genry logger to the console logger to get nice output

        $genry->watch();
    }
}
