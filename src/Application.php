<?php
namespace MD\Genry;

use MD\Foundation\Debug\Debugger;
use Symfony\Component\Yaml\Yaml;

use Splot\Framework\Application\AbstractApplication;
use Splot\Framework\Framework;

class Application extends AbstractApplication
{

    protected $name = 'Genry';
    protected $version = '0.3.1';

    private $userModules = array();
    private $userConfig = array();

    public function bootstrap(array $option = array()) {
        if ($this->container->getParameter('mode') !== Framework::MODE_CONSOLE) {
            throw new \RuntimeException('Genry can only be run in console!');
        }
        parent::bootstrap();
    }

    public function loadParameters() {
        $cwd = getcwd() . DS;
        $parameters = array(
            'application_dir' => $cwd,
            'root_dir' => $cwd,
            'config_dir' => $cwd .'config'. DS,
            'cache_dir' => $cwd .'.cache'. DS,
            'web_dir' => $cwd,
            'templates_dir' => $cwd .'_templates'. DS,
            'data_dir' => $cwd .'_data'. DS
        );

        if (file_exists($yml = $cwd . '.genry.yml')) {
            $cfg = Yaml::parse(file_get_contents($yml));

            // parse dirs
            foreach(array('cache_dir', 'data_dir', 'templates_dir', 'web_dir') as $paramName) {
                $parameters[$paramName] = isset($cfg[$paramName]) ? $cwd . trim($cfg[$paramName], DS) . DS : $parameters[$paramName];
            }

            // remember what modules to load
            $this->userModules = isset($cfg['modules']) && is_array($cfg['modules']) ? $cfg['modules'] : array();

            // everything left will be stored in the config
            $this->userConfig = $cfg;
        }

        return $parameters;
    }

    public function configure() {
        parent::configure();
        $this->container->get('config')->apply($this->userConfig);
    }

    public function loadModules() {
        $modules = array(
            new \Splot\TwigModule\SplotTwigModule(),
            new \Splot\AssetsModule\SplotAssetsModule(),

            new \MD\Genry\GenryModule()
        );

        // load any user modules defined in the .genry.yml file
        foreach($this->userModules as $name) {
            $modules[] = new $name();
        }

        return $modules;
    }

}