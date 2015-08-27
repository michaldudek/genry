<?php
namespace Genry;

use MD\Foundation\Debug\Debugger;
use Symfony\Component\Yaml\Yaml;

use Splot\Cache\Store\MemoryStore;
use Splot\Framework\Application\AbstractApplication;
use Splot\Framework\DependencyInjection\ContainerCache;
use Splot\Framework\Framework;

class Application extends AbstractApplication
{

    protected $name = 'Genry';
    protected $version = '0.4.0-dev';

    private $userModules = array();
    private $userConfig = array();

    public function loadParameters($env, $debug) {
        $cwd = getcwd();
        $parameters = array(
            'application_dir' => $cwd,
            'root_dir' => $cwd,
            'config_dir' => $cwd .'/config',
            'cache_dir' => $cwd .'/.cache',
            'web_dir' => $cwd,
            'templates_dir' => $cwd .'/_templates',
            'data_dir' => $cwd .'/_data'
        );

        if (file_exists($yml = $cwd . '/.genry.yml')) {
            $cfg = Yaml::parse(file_get_contents($yml));

            // parse dirs
            foreach(array('cache_dir', 'data_dir', 'templates_dir', 'web_dir') as $paramName) {
                $parameters[$paramName] = isset($cfg[$paramName]) ? $cwd .'/'. trim($cfg[$paramName], DS) : $parameters[$paramName];
            }

            // remember what modules to load
            $this->userModules = isset($cfg['modules']) && is_array($cfg['modules']) ? $cfg['modules'] : array();

            // everything left will be stored in the config
            $this->userConfig = $cfg;
        }

        return $parameters;
    }

    public function loadModules($env, $debug) {
        $modules = array(
            new \Splot\TwigModule\SplotTwigModule(),
            new \Splot\AssetsModule\SplotAssetsModule(),

            new \Genry\GenryModule()
        );

        // load any user modules defined in the .genry.yml file
        foreach($this->userModules as $name) {
            $modules[] = new $name();
        }

        return $modules;
    }

    /**
     * Provide a cache for the container.
     *
     * @param string $env Env in which the app is ran.
     * @param boolean $debug Is debug on?
     *
     * @return ContainerCache
     */
    public function provideContainerCache($env, $debug)
    {
        return new ContainerCache(new MemoryStore());
    }

    public function configure() {
        parent::configure();
        $this->container->get('config')->apply($this->userConfig);
    }

    public function run() {
        if ($this->container->getParameter('mode') !== Framework::MODE_CONSOLE) {
            throw new \RuntimeException('Genry can only be run in console!');
        }
        parent::run();
    }
}