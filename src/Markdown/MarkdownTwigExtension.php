<?php
namespace Genry\Markdown;

use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

use MD\Foundation\Exceptions\NotFoundException;

use Genry\Markdown\Markdown;

/**
 * Markdown extension for Twig.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
class MarkdownTwigExtension extends Twig_Extension
{

    /**
     * Markdown class.
     *
     * @var Markdown
     */
    protected $markdown;

    /**
     * Templates directory path.
     *
     * @var string
     */
    protected $templatesDir;

    /**
     * Constructor.
     *
     * @param Markdown $markdown     Markdown service.
     * @param string   $templatesDir Templates directory path.
     */
    public function __construct(Markdown $markdown, $templatesDir)
    {
        $this->markdown = $markdown;
        $this->templatesDir = rtrim($templatesDir, DS) . DS;
    }

    /**
     * Returns functions to be added to Twig.
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('markdown', array($this, 'parseMarkdownFile'), array('is_safe' => array('html')))
        );
    }

    /**
     * Returns filters to be added to Twig.
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('markdown', array($this, 'parseMarkdown'), array('is_safe' => array('html')))
        );
    }

    /**
     * Parses a markdown file.
     *
     * @param  string $file Path to the file.
     *
     * @return string
     */
    public function parseMarkdownFile($file)
    {
        $filePath = $this->templatesDir . trim($file, DS);
        if (!file_exists($filePath)) {
            throw new NotFoundException('Could not find '. $file .' in the templates dir '. $this->templatesDir);
        }

        $markdown = file_get_contents($filePath);
        return $this->parseMarkdown($markdown);
    }

    /**
     * Parses a markdown string.
     *
     * @param  string $markdown Markdown formatted string.
     *
     * @return string
     */
    public function parseMarkdown($markdown)
    {
        return $this->markdown->parse($markdown);
    }

    /**
     * Returns the name of this extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'genry.markdown';
    }
}
