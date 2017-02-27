<?php
/**
 * Created by Antony Repin
 * Date: 26.02.2017
 * Time: 22:07
 */

/**
 * Registering autoloader
 */
$loader = new \Phalcon\Loader();

$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->formsDir
    ]
)->register();

