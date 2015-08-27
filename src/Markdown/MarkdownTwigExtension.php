<?php
namespace Genry\Markdown;

use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

use MD\Foundation\Exceptions\NotFoundException;

use Genry\Markdown\Markdown;

class MarkdownTwigExtension extends Twig_Extension
{

    protected $markdown;

    protected $templatesDir;

    public function __construct(Markdown $markdown, $templatesDir)
    {
        $this->markdown = $markdown;
        $this->templatesDir = rtrim($templatesDir, DS) . DS;
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('markdown', array($this, 'parseMarkdownFile'), array('is_safe' => array('html')))
        );
    }

    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('markdown', array($this, 'parseMarkdown'), array('is_safe' => array('html')))
        );
    }

    public function parseMarkdownFile($file)
    {
        $filePath = $this->templatesDir . trim($file, DS);
        if (!file_exists($filePath)) {
            throw new NotFoundException('Could not find '. $file .' in the templates dir '. $this->templatesDir);
        }

        $markdown = file_get_contents($filePath);
        return $this->parseMarkdown($markdown);
    }

    public function parseMarkdown($markdown)
    {
        return $this->markdown->parse($markdown);
    }

    public function getName()
    {
        return 'genry.markdown';
    }
}
