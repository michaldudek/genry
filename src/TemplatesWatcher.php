<?php
namespace Genry;

use MD\Foundation\Utils\FilesystemUtils;

use Genry\FileWatcher\FileWatcherInterface;

class TemplatesWatcher implements FileWatcherInterface
{

    protected $templatesDir;

    public function __construct($templatesDir) {
        $this->templatesDir = $templatesDir;
    }

    public function filesToWatch() {
        $files = FilesystemUtils::glob($this->templatesDir .'{,**/}*.html.twig', GLOB_BRACE);
        return $files;
    }

}