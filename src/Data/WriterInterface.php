<?php
namespace MD\Genry\Data;

interface WriterInterface
{

    /**
     * Write data.
     * 
     * @param  string $name Name of the data to write.
     * @param  array  $data Data to be written.
     */
    function write($name, array $data);

}