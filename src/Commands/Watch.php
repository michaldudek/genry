<?php
namespace Genry\Commands;

use MD\Foundation\Utils\FilesystemUtils;

use Splot\Framework\Console\AbstractCommand;

use Genry\Page;

class Watch extends AbstractCommand
{

    protected static $name = 'watch';
    protected static $description = 'Watch the templates folder for any changes.';

    public function execute()
    {
        $this->writeln('Watching the project for changes...');

        $genry = $this->get('genry');
        $genry->setLogger($this->getLogger()); // set genry logger to the console logger to get nice output

        $genry->watch();
    }
}
