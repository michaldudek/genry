<?php
namespace Genry\Data;

use SplFileInfo;
use RuntimeException;

use MD\Foundation\Exceptions\NotFoundException;

use Genry\Data\LoaderInterface;

/**
 * Data loader.
 *
 * @author Michał Pałys-Dudek <michal@michaldudek.pl>
 */
class Loader implements LoaderInterface
{

    /**
     * Data directory path.
     *
     * @var string
     */
    protected $dataDir;

    /**
     * Constructor.
     *
     * @param string $dataDir Data directory path.
     */
    public function __construct($dataDir)
    {
        $this->dataDir = rtrim($dataDir, DS) . DS;
    }

    /**
     * Loads data with the given name.
     *
     * @param  string $name Name of the data to load.
     *
     * @return array
     */
    public function load($name)
    {
        $file = new SplFileInfo($this->dataDir . $name);
        if (!$file->isFile()) {
            throw new NotFoundException('Could not find data file '. $name .' in '. $this->dataDir);
        }

        $json = file_get_contents($file->getPathname());
        $data = json_decode($json, true);

        if (!$data) {
            throw new RuntimeException('Invalid JSON found in data file '. $name);
        }

        return $data;
    }

    /**
     * Returns the directory data path.
     *
     * @return string
     */
    public function getDataDir()
    {
        return $this->dataDir;
    }
}
