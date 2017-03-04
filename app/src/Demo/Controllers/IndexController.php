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
    /**
     * Login page
     */
    public function loginAction()
    {
        $this->view->setTemplateAfter("form-login");
    }

    /**
     * Registration page
     */
    public function registerAction()
    {
        if ($this->isAllowed("guest", "Index", "register")) {
            $this->view->setTemplateAfter("form-register");
        }
    }

    /**
     * Reset password page
     */
    public function resetAction()
    {
        if ($this->isAllowed("staff", "Users", "reset") ||
            $this->isAllowed("cutomer", "Users", "reset")) {
            $this->view->setTemplateAfter("form-reset");
        }
    }

    /**
     * Logged-in an authorized users only
     */
    public function dashboardAction()
    {
        die(__FILE__);
        $this->view->setTemplateAfter("dashboard");
    }


}
