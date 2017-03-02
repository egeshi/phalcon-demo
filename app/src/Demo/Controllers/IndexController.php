<?php
/**
 * Created by Antony Repin
 * Date: 26.02.2017
 * Time: 19:58
 */

namespace Demo\Controllers;

use Demo\Controllers\Core\ModelviewController;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher as Dispatcher;

/**
 * Class IndexController
 *
 * @package Demo\Controllers
 */
class IndexController extends ModelviewController
{
    public function loginAction()
    {
        $this->view->setTemplateAfter("form-login");
    }

    /**
     * Registration page
     */
    public function registerAction()
    {
        $this->view->setTemplateAfter("form-register");
    }

    /**
     * Resetpassword page
     */
    public function resetAction(){
        $this->view->setTemplateAfter("form-reset");
    }



}
