<?php
namespace MD\Genry\Data;

interface LoaderInterface
{

    /**
     * Load data from the source with the name $name.
     * 
     * @param  string $name Name of the data to load.
     * @return array
     */
    function load($name);

}