<?php
/**
 * Created by Antony Repin
 * Date: 27.02.2017
 * Time: 1:27
 */

$router = $di->getRouter();

/**
 * Index page
 */
$router->addGet("/", [
    "controller" => "Demo\\Controllers\\Index",
    "action"     => "index",
]);


/**
 * Not found handler
 */
/*$app->notFound(function () use($app, $di) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $di['view']->render('404');
});*/

$router->handle();
