<?php
namespace Genry;

use MD\Foundation\MagicObject;

use SplFileInfo;

/**
 * Info about page/template that is being rendered.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
class Page extends MagicObject
{

    /**
     * Available properties on this object.
     *
     * @var array
     */
    protected $__properties = array(
        'template_name' => null,
        'template_file' => null,
        'output_name' => null,
        'output_file' => null,
        'parameters' => array()
    );

    /**
     * Sets the template file.
     *
     * @param SplFileInfo $templateFile The template file for this page.
     */
    public function setTemplateFile(SplFileInfo $templateFile)
    {
        $this->__properties['template_file'] = $templateFile;
    }

    /**
     * Sets the output file.
     *
     * @param SplFileInfo $outputFile The output file for this page.
     */
    public function setOutputFile(SplFileInfo $outputFile)
    {
        $this->__properties['output_file'] = $outputFile;
    }

    /**
     * Converts the page to string by returning its template name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTemplateName();
    }
}
