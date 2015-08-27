<?php
namespace Genry\Markdown;

use Michelf\MarkdownExtra;

/**
 * Markdown service.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
class Markdown
{

    /**
     * Parse the given Markdown string using MarkdownExtra syntax
     * and return HTML string.
     *
     * @param  string $markdown Markdown formated string.
     *
     * @return string
     */
    public function parse($markdown)
    {
        $html = MarkdownExtra::defaultTransform($markdown);
        return $html;
    }
}
