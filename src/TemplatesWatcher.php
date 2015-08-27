<?php
namespace Genry;

use MD\Foundation\Utils\FilesystemUtils;

use Genry\FileWatcher\FileWatcherInterface;

/**
 * Returns template files to be watched.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
class TemplatesWatcher implements FileWatcherInterface
{

    /**
     * Templates directory path.
     *
     * @var string
     */
    protected $templatesDir;

    /**
     * Constructor.
     *
     * @param string $templatesDir Templates directory path.
     */
    public function __construct($templatesDir)
    {
        $this->templatesDir = $templatesDir;
    }

    /**
     * Returns files to watch.
     *
     * @return array
     */
    public function filesToWatch()
    {
        $files = FilesystemUtils::glob($this->templatesDir .'{,**/}*.html.twig', GLOB_BRACE);
        return $files;
    }
}
