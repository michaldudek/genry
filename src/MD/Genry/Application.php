<?php
namespace MD\Genry;

use MD\Foundation\Debug\Debugger;

use Splot\Framework\Application\AbstractApplication;
use Splot\Framework\Framework;

class Application extends AbstractApplication
{

    protected $name = 'Genry';
    protected $version = '0.0.0';

    public function bootstrap(array $option = array()) {
        if ($this->container->getParameter('mode') !== Framework::MODE_CONSOLE) {
            throw new \RuntimeException('Genry can only be run in console!');
        }
        parent::bootstrap();
    }

    public function loadParameters() {
        $applicationDir = dirname(Debugger::getClassFile(get_called_class())) . DS;

        return array(
            'application_dir' => $applicationDir,
            'root_dir' => $applicationDir,
            'config_dir' => $applicationDir .'config'. DS,
            'cache_dir' => $applicationDir .'.cache'. DS,
            'web_dir' => $applicationDir,
            'templates_dir' => $applicationDir .'_templates'. DS,
            'data_dir' => $applicationDir .'_data'. DS
        );
    }

    public function loadModules() {
        $modules = array(
            new \Splot\TwigModule\SplotTwigModule(),
            new \Splot\AssetsModule\SplotAssetsModule(),

            new \MD\Genry\GenryModule()
        );
        return $modules;
    }

}