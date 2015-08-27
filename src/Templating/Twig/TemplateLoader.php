<?php
namespace Genry\Templating\Twig;

use Twig_Loader_Filesystem;

/**
 * Twig templates loader.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
class TemplateLoader extends Twig_Loader_Filesystem
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
        parent::__construct($templatesDir);
        $this->templatesDir = $templatesDir;
    }

    /**
     * Checks if the template is fresh.
     *
     * @param  string  $name Template name.
     * @param  integer $time Compare against access time.
     *
     * @return boolean
     */
    public function isFresh($name, $time)
    {
        // always force reload
        return false;
    }

    /**
     * Get cache key name for the given template.
     *
     * @param string $name Name of the template.
     */
    public function getCacheKey($name)
    {
        $key = parent::getCacheKey($name);
        return $key . filemtime($key);
    }
}
