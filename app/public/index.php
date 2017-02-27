<?php
/**
 * Created by Antony Repin
 * Date: 26.02.2017
 * Time: 14:58
 */
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro as Application;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH);

try{
    include APP_PATH . '/Demo/DefaultServices.php';
    $serviceContainer = new \Demo\DefaultServices(new FactoryDefault());
    $di = $serviceContainer->getServices();
    $config = $serviceContainer->getConfig();
    include APP_PATH . '/Demo/Loader.php';
    $app = new Application($di);
    include APP_PATH . '/config/routing.php';
    $app->handle();

} catch (\Exception $e) {
    echo "<h3>Error: ". $e->getMessage() . '</h3>';
    "<pre>".print_r($e->getTraceAsString())."</pre>";
}

