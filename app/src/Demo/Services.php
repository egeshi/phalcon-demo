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
use Phalcon\Session\Adapter\Redis as SessionAdapterRedis;
use Phalcon\Session\Adapter\Files as SessionAdapterFiles;
use Phalcon\Config\Adapter\Ini as ConfigIni;
use Phalcon\Mvc\Router;
use Demo\Plugins\JsonResponsePlugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;

/**
 * Class DefaultServices
 * @package Demo
 */
class Services
{

    const APP_CONFIG = DS . "config" . DS . "config.ini";
    const ROUTES_CONFIG = DS . "config" . DS . "config.ini";

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
        $this->routes = new ConfigIni(APP_PATH . "/../config/routes.ini");

        $this
            ->setUrlResolver()
            ->setDb()
            ->setFlash()
            ->setView()
            ->setSession()
            ->setModelsMetadata()
            ->setDispatcher()
            ->setRoutes();
    }

    /**
     * Get configuration object
     *
     * @param string|null $location
     * @return \Phalcon\Config\Adapter\Ini
     */
    public function getConfig($location = null)
    {

        if (!$this->config) {
            if ($location) {
                return new ConfigIni(APP_PATH . $location);
            }
            return new ConfigIni(APP_PATH . "/../" . self::APP_CONFIG);
        }

        $this->di->set("config", $this->config, true);

        return $this->config;

    }

    /**
     * Get DI factory
     *
     * @return \Phalcon\Di\FactoryDefault
     */
    public function getServices()
    {
        return $this->di;
    }

    /**
     * Configure URL resolver
     *
     * @return $this
     */
    private function setUrlResolver()
    {

        $config = $this->config;
        $this->di->set("url", function () use ($config) {
            $url = new UrlProvider();
            $url->setBaseUri($config->application->baseUri);
            return $url;
        });

        return $this;
    }

    /**
     * Initialize view and define Volt and PHP template engines
     *
     * @return $this
     */
    private function setView()
    {

        $view = new View();
        $view->setDI($this->di);
        $view->setViewsDir(APP_PATH . DS . $this->config->application->viewsDir);

        $view->registerEngines([
            '.volt' => function ($view) {
                $volt = new VoltEngine($view, $this->di);

                $volt->setOptions([
                    'compiledPath' => BASE_PATH . DS . $this->config->application->cacheDir,
                    'compiledSeparator' => '_',
                ]);

                return $volt;
            },
            '.phtml' => PhpEngine::class

        ]);

        $em = $this->di->getShared('eventsManager');

        $em->attach(
            "view",
            function (Event $event, $view) {
                if ($view->getActiveRenderPath()) {
                    echo '<pre class="container-fluid"><div class="row"><div class="events col-sm-12">' . $event->getType() . ": " . $view->getActiveRenderPath() . PHP_EOL . "</div></div></pre>";
                }
            }
        );

        $view->setEventsManager($em);

        $this->di->set("view", $view);

        return $this;
    }

    /**
     * Initialize DB adapter
     *
     * @return $this
     */
    private function setDb()
    {

        $class = 'Phalcon\Db\Adapter\Pdo\\' . $this->config->database->adapter;
        $params = [
            'host' => $this->config->database->host,
            'username' => $this->config->database->username,
            'password' => $this->config->database->password,
            'dbname' => $this->config->database->dbname,
            'charset' => $this->config->database->charset
        ];

        $connection = new $class($params);

        $this->di->set("db", $connection);

        return $this;
    }

    /**
     * Models setup
     *
     * @return $this
     */
    private function setModelsMetadata()
    {

        $this->di->setShared("modelsMetadata", function () {
            return new MetaDataAdapter();
        });

        return $this;
    }

    /**
     * Set session adapter
     * @return $this
     */
    private function setSession()
    {

        $config = $this->config;

        $this->di->setShared('session', function () use ($config) {

            if ($config->redis->enabled) {
                $session = new SessionAdapterRedis($config->redis->toArray());
            } else {
                $session = new SessionAdapterFiles();
            }

            $session->start();

            return $session;
        });

        return $this;
    }

    /**
     * Pre-define Flash message classes with Bootstrap's ones
     *
     * @return $this
     */
    private function setFlash()
    {

        $this->di->set('flash', function () {
            return new Flash([
                'error' => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice' => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);
        });

        return $this;
    }

    /**
     * Import and populate routes from config
     *
     * @return $this
     */
    private function setRoutes()
    {

        $routesConfig = $this->routes;

        $this->di->set("router", function () use ($routesConfig) {

            $router = new Router(false);

            foreach ($routesConfig as $uri => $routeData) {

                $methods = explode(",", $routeData->method);

                foreach ($methods as $m){
                    $method = 'add' . ucfirst(strtolower($m));
                    $router->$method($uri, [
                        "controller" => $routeData->controller,
                        "action" => $routeData->action,
                    ]);
                }
            }

            $router->notFound([
                "controller" => "Demo\\Controllers\\Error",
                "action" => "show404",
            ]);

            return $router;
        });

        return $this;

    }

    /**
     *
     */
    private function setDispatcher()
    {

        $dispatcher = new Dispatcher();

        $em = new EventsManager();

        $em->attach("dispatch:beforeExecuteRoute", new \Demo\Plugins\JsonResponsePlugin());
        $em->attach("dispatch:beforeExecuteRoute", new \Demo\Plugins\SecurityPlugin());

        $dispatcher->setEventsManager($em);

        $this->di->set("dispatcher", $dispatcher);

        $em = $this->di->getShared("eventsManager");

        $em->attach('view:afterRender', function (Event $event, $view) {
            // poor workaround for possible bug in render() method
            // of \Phalcon\Mvc\View which results in rendering blank page
            // needs to be investigated in depth
            $event->stop();
            exit();
        });

        return $this;

    }

}
