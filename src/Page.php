<?php
namespace Genry;

use MD\Foundation\MagicObject;

use SplFileInfo;

class Page extends MagicObject
{

    protected $__properties = array(
        'template_name' => null,
        'template_file' => null,
        'output_name' => null,
        'output_file' => null,
        'parameters' => array()
    );

    public function setTemplateFile(SplFileInfo $templateFile) {
        $this->__properties['template_file'] = $templateFile;
    }

    public function setOutputFile(SplFileInfo $outputFile) {
        $this->__properties['output_file'] = $outputFile;
    }

    public function __toString() {
        return $this->getTemplateName();
    }

}