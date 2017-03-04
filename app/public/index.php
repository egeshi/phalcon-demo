<?php
/**
 * Created by Antony Repin
 * Date: 26.02.2017
 * Time: 14:58
 */

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Demo\Services;


defined('APP_ENV') || define('APP_ENV', (getenv('PHALCON_ENV') ? getenv('PHALCON_ENV') : 'development'));
define('BASE_PATH', dirname(__DIR__));
define("DS", DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(__DIR__) . DS . 'src');

require_once BASE_PATH . DS . ".." . DS . "vendor" . DS . "autoload.php";

try {

    $serviceContainer = new Services(new FactoryDefault());
    $di = $serviceContainer->getServices();
    $config = $serviceContainer->getConfig();
    $app = new Application($di);
    $app->handle()->getContent();

} catch (\Exception $e) {
    echo "<h3>Error: " . $e->getMessage() . '</h3>';
    var_dump($e->getTrace());
}

