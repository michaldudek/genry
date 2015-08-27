<?php
namespace Genry\Templating\Twig;

use Twig_Loader_Filesystem;

class TemplateLoader extends Twig_Loader_Filesystem
{

    protected $templatesDir;

    public function __construct($templatesDir)
    {
        parent::__construct($templatesDir);
        $this->templatesDir = $templatesDir;
    }

    public function isFresh($name, $time)
    {
        // always force reload
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheKey($name)
    {
        $key = parent::getCacheKey($name);
        return $key . filemtime($key);
    }
}
