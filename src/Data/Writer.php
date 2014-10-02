<?php
namespace MD\Genry\Data;

use SplFileInfo;

use Symfony\Component\Filesystem\Filesystem;

use MD\Genry\Data\WriterInterface;

class Writer implements WriterInterface
{

    protected $dataDir;

    /**
     * Filesystem service.
     * 
     * @var Filesystem
     */
    protected $filesystem;

    public function __construct(Filesystem $filesystem, $dataDir) {
        $this->filesystem = $filesystem;
        $this->dataDir = rtrim($dataDir, DS) . DS;
    }

    public function write($name, array $data) {
        // keep php 5.3 compatible
        $pretty = defined('JSON_PRETTY_PRINT') ? constant('JSON_PRETTY_PRINT') : null;
        
        $data = json_encode($data, $pretty);
        $this->filesystem->dumpFile($this->dataDir . $name, $data);
        return true;
    }

    public function getDataDir() {
        return $this->dataDir;
    }

}