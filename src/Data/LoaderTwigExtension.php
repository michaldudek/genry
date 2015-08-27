<?php
namespace Genry\Data;

use Twig_Extension;

use Genry\Data\LoaderInterface;

/**
 * Twig extension that allows loading data in templates.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
class LoaderTwigExtension extends Twig_Extension
{

    /**
     * The data loader.
     *
     * @var LoaderInterface
     */
    protected $dataLoader;

    /**
     * Constructor.
     *
     * @param LoaderInterface $dataLoader Loader interface.
     */
    public function __construct(LoaderInterface $dataLoader)
    {
        $this->dataLoader = $dataLoader;
    }

    /**
     * Returns Twig functions registered by this extension.
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('data', array($this, 'loadData'))
        );
    }

    /**
     * Returns the name of this extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'genry.data_loader';
    }

    /**
     * Loads the data.
     *
     * @param  string $name Name of the data to load.
     *
     * @return array
     */
    public function loadData($name)
    {
        return $this->dataLoader->load($name);
    }
}
