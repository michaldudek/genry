<?php
namespace Genry\Data;

use SplFileInfo;
use RuntimeException;

use MD\Foundation\Exceptions\NotFoundException;

use Genry\Data\LoaderInterface;

class Loader implements LoaderInterface
{

    protected $dataDir;

    public function __construct($dataDir) {
        $this->dataDir = rtrim($dataDir, DS) . DS;
    }

    public function load($name) {
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

    public function getDataDir() {
        return $this->dataDir;
    }

}