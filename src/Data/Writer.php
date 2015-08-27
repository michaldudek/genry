<?php
namespace Genry\Data;

use SplFileInfo;

use Symfony\Component\Filesystem\Filesystem;

use Genry\Data\WriterInterface;

/**
 * Writes data.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
class Writer implements WriterInterface
{
    /**
     * Data directory path.
     *
     * @var string
     */
    protected $dataDir;

    /**
     * Filesystem service.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Constructor.
     *
     * @param Filesystem $filesystem Filesystem service.
     * @param string     $dataDir    Data directory path.
     */
    public function __construct(Filesystem $filesystem, $dataDir)
    {
        $this->filesystem = $filesystem;
        $this->dataDir = rtrim($dataDir, DS) . DS;
    }

    /**
     * Writes data.
     *
     * @param  string $name Name of the data.
     * @param  array  $data The data.
     *
     * @return boolean
     */
    public function write($name, array $data)
    {
        $data = json_encode($data, JSON_PRETTY_PRINT);
        $this->filesystem->dumpFile($this->dataDir . $name, $data);
        return true;
    }

    /**
     * Returns the data dir.
     *
     * @return string
     */
    public function getDataDir()
    {
        return $this->dataDir;
    }
}
