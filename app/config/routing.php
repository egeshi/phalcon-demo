<?php
/**
 * Created by Antony Repin
 * Date: 27.02.2017
 * Time: 1:27
 */

/**
 * Index
 */
$app->get('/', function () use ($di) {
    echo $di['view']->render('index');
});

/**
 * Add your routes here
 */

/**
 * Not found handler
 */
$app->notFound(function () use($app, $di) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $di['view']->render('404');
});
