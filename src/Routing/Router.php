<?php
namespace Genry\Routing;

use Genry\Page;

/**
 * Genry Router.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
class Router
{
    /**
     * Web directory path.
     *
     * @var string
     */
    protected $webDir;

    /**
     * Constructor.
     *
     * @param string $webDir Web directory path.
     */
    public function __construct($webDir)
    {
        $this->webDir = $webDir;
    }

    /**
     * Generates a link to a file at the given path.
     *
     * @param  string $path     Path to the file.
     * @param  Page   $fromPage Page which will link to that file.
     *
     * @return string
     */
    public function generateLink($path, Page $fromPage)
    {
        $relativeTo = $fromPage->getOutputFile()->getPath();
        $relativeTo = rtrim($relativeTo, DS) . DS;

        if (stripos($relativeTo, $this->webDir) !== 0) {
            throw new \RuntimeException('The rendered page is not inside the web dir!');
        }

        $relativeTo = mb_substr($relativeTo, mb_strlen($this->webDir));
        $path = ltrim($path, '/');

        $relative = '';
        foreach (explode('/', $relativeTo) as $dir) {
            $relative .= !empty($dir) ? '../' : '';
        }

        return $relative . $path;
    }
}
