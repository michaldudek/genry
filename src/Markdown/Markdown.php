<?php
namespace MD\Genry\Markdown;

use Michelf\MarkdownExtra;

class Markdown
{

    /**
     * Parse the given Markdown string using MarkdownExtra syntax
     * and return HTML string.
     * 
     * @param  string $markdown Markdown formated string.
     * @return string
     */
    public function parse($markdown) {
        $html = MarkdownExtra::defaultTransform($markdown);
        return $html;
    }

}