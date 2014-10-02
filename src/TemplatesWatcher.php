<?php
namespace MD\Genry;

use MD\Foundation\Utils\FilesystemUtils;

use MD\Genry\FileWatcher\FileWatcherInterface;

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