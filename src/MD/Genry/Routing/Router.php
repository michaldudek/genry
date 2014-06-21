<?php
namespace MD\Genry\Routing;

use MD\Genry\Page;

class Router
{

    protected $webDir;

    public function __construct($webDir) {
        $this->webDir = $webDir;
    }

    public function generateLink($path, Page $fromPage) {
        $relativeTo = $fromPage->getOutputFile()->getPath();
        $relativeTo = rtrim($relativeTo, DS) . DS;

        if (stripos($relativeTo, $this->webDir) !== 0) {
            throw new \RuntimeException('The rendered page is not inside the web dir!');
        }

        $relativeTo = mb_substr($relativeTo, mb_strlen($this->webDir));
        $path = ltrim($path, '/');

        $relative = '';
        foreach(explode('/', $relativeTo) as $dir) {
            $relative .= !empty($dir) ? '../' : '';
        }

        return $relative . $path;
    }

}