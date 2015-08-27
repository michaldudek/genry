<?php
namespace Genry\Data;

/**
 * Interface for Data Loader.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
interface LoaderInterface
{

    /**
     * Load data from the source with the name $name.
     *
     * @param  string $name Name of the data to load.
     *
     * @return array
     */
    public function load($name);
}
