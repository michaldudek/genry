<?php
namespace Genry\Data;

use Twig_Extension;

use Genry\Data\LoaderInterface;

class LoaderTwigExtension extends Twig_Extension
{

    protected $dataLoader;

    public function __construct(LoaderInterface $dataLoader) {
        $this->dataLoader = $dataLoader;
    }

    /**
     * Returns Twig functions registered by this extension.
     * 
     * @return array
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('data', array($this, 'loadData'))
        );
    }

    /**
     * Returns the name of this extension.
     * 
     * @return string
     */
    public function getName() {
        return 'genry.data_loader';
    }

    public function loadData($name) {
        return $this->dataLoader->load($name);
    }

}