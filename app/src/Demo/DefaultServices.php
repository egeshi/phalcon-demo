<?php
/**
 * Created by Antony Repin
 * Date: 26.02.2017
 * Time: 18:56
 */

namespace Demo;

use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Events\Event;

/**
 * Class DefaultServices
 * @package Demo
 */
class DefaultServices
{

    const DEFAULT_FILE = DS."config".DS."config.ini";

    /**
     * @var \Phalcon\Di\FactoryDefault
     */
    private $di;

    /**
     * @var \Phalcon\Config\Adapter\Ini
     */
    private $config;

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
            ->setDb()
            ->setFlash()
            ->setView()
            ->setSession()
            ->setModelsMetadata()
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
            return new \Phalcon\Config\Adapter\Ini(APP_PATH."/../".self::DEFAULT_FILE);
        }

        return $this->config;

    }

    /**
     * @return $this
     */
    private function setUrlResolver(){

        $config = $this->config;
        $this->di->set("url", function() use ($config){
            $url = new UrlProvider();
            $url->setBaseUri($config->application->baseUri);
            return $url;
        });

        return $this;
    }

    /**
     * @return $this
     */
    private function setView(){

        $view = new View();
        $view->setDI($this->di);
        $view->setViewsDir(APP_PATH.DS.$this->config->application->viewsDir);

        $view->registerEngines([
            '.volt' => function ($view) {
                $volt = new VoltEngine($view, $this->di);

                $volt->setOptions([
                    'compiledPath' => BASE_PATH.DS.$this->config->application->cacheDir,
                    'compiledSeparator' => '_',
                ]);

                return $volt;
            },
            '.phtml' => PhpEngine::class

        ]);

        $eventsManager = new EventsManager();

        $eventsManager->attach(
            "view",
            function (Event $event, $view) {
                if ($view->getActiveRenderPath()){
                    echo '<pre><div id="debugPane"><div class="event">' . $event->getType() . ": " . $view->getActiveRenderPath() . PHP_EOL . "</div></div></pre>";
                }

                if ($event->getType() == 'afterRender'){
                    // poor workaround for possible render() method of \Phalcon\Mvc\View
                    // which results in rendering blank page
                    // needs to be investigated deeper
                    if(!$event->isStopped()){
                        $event->stop();
                        exit();
                    }
                }
            }
        );

        $view->setEventsManager($eventsManager);

        $this->di->set("view", $view);

        return $this;
    }

    /**
     * @return $this
     */
    private function setDb(){

        $class = 'Phalcon\Db\Adapter\Pdo\\' . $this->config->database->adapter;
        $params = [
            'host'     => $this->config->database->host,
            'username' => $this->config->database->username,
            'password' => $this->config->database->password,
            'dbname'   => $this->config->database->name,
            'charset'  => $this->config->database->charset
        ];

        $connection = new $class($params);

        $this->di->set("db", $connection);

        return $this;
    }

    /**
     * @return $this
     */
    private function setModelsMetadata(){
        
        $this->di->setShared("modelsMetadata", function () {
            return new MetaDataAdapter();
        });

        return $this;
    }

    private function setSession(){
        $this->di->setShared('session', function () {
            $session = new SessionAdapter();
            $session->start();

            return $session;
        });

        return $this;
    }

    private function setFlash(){

        $this->di->set('flash', function () {
            return new Flash([
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);
        });

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
