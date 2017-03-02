<?php
/**
 * Created by Antony Repin
 * Date: 01.03.2017
 * Time: 13:37
 */

namespace Demo\Controllers;

use Demo\Controllers\Core\ModelviewController;

/**
 * Class ErrorController
 * @package Demo\Controllers
 */
class ErrorController extends ModelviewController
{
    public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {

    }

    /**
     * Show standard 404 error page
     */
    public function show404Action()
    {
        $this->view->disable();
        $this->response->setStatusCode(404, "Not Found!");
        $this->view->render('statuses', '404');
    }
}
