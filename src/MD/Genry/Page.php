<?php
namespace MD\Genry;

use MD\Foundation\MDObject;

use SplFileInfo;

class Page extends MDObject
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