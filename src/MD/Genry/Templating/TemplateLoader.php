<?php
namespace MD\Genry\Templating;

use Twig_Loader_Filesystem;

class TemplateLoader extends Twig_Loader_Filesystem
{

    protected $templatesDir;

    public function __construct($templatesDir) {
        parent::__construct($templatesDir);
        $this->templatesDir = $templatesDir;
    }

}