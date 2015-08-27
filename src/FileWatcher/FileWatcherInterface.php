<?php
namespace Genry\FileWatcher;

/**
 * Interface for a file watcher.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
interface FileWatcherInterface
{
    /**
     * Should return a list of files to watch.
     *
     * @return array
     */
    public function filesToWatch();
}
