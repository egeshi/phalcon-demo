<?php
/**
 * Created by Antony Repin
 * Date: 26.02.2017
 * Time: 18:56
 */

namespace Demo;

use Phalcon\Mvc\View\Simple as View;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\Application;

/**
 * Class DefaultServices
 * @package Demo
 */
class DefaultServices
{

    const DEFAULT_FILE = "/config/config.ini";

    /**
     * @var \Phalcon\Di\FactoryDefault
     */
    protected $di;

    /**
     * @var \Phalcon\Config\Adapter\Ini
     */
    protected $config;

    /**
     * Services constructor.
     * @param \Phalcon\Di\FactoryDefault $di
     * @param string $location
     */
    public function __construct(\Phalcon\Di\FactoryDefault $di, $location = null)
    {

        $this->di = $di;
        $this->config = is_null($location) ? $this->getConfig() : $this->getConfig($location);

        $this
            ->setUrlResolver()
            ->setView()
        ;
    }

    /**
     * @param string|null $location
     * @return \Phalcon\Config\Adapter\Ini
     */
    public function getConfig($location=null){

        if (!$this->config){
            if ($location){
                return new \Phalcon\Config\Adapter\Ini(APP_PATH . $location);
            }
            return new \Phalcon\Config\Adapter\Ini(APP_PATH . self::DEFAULT_FILE);
        }

        return $this->config;

    }

    /**
     * @return Config
     */
    public function setUrlResolver(){
        $url = new UrlProvider();
        $url->setBaseUri($this->config->application->baseUri);

        return $this;
    }

    /**
     * @return View
     */
    public function setView(){

        $view = new View();
        $view->setViewsDir($this->config->application->viewsDir);
        $this->di->set("view", $view);

        return $this;
    }

    /**
     * Get DI factory
     * @return \Phalcon\Di\FactoryDefault
     */
    public function getServices(){
        return $this->di;
    }

}
