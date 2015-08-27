<?php
namespace Genry\Data;

/**
 * Interface for writing data.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
interface WriterInterface
{

    /**
     * Write data.
     *
     * @param  string $name Name of the data to write.
     * @param  array  $data Data to be written.
     */
    public function write($name, array $data);
}
