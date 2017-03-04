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

        $this->view->pick("errors/403");
    }

    /**
     * Reset password page
     */
    public function resetAction()
    {
        if ($this->isAllowed("staff", "Index", "reset") ||
            $this->isAllowed("cutomer", "Index", "reset")
        ) {
            $this->view->setTemplateAfter("form-reset");
        }

        $this->view->pick("errors/403");
    }

    /**
     * Logged-in an authorized users only
     */
    public function dashboardAction()
    {
        if ($this->isAllowed("staff", "Index", "dashboard") ||
            $this->isAllowed("customer", "Index", "dashboard")
        ) {
            $user = $this->session->get("user");
            $this->view->setTemplateAfter("dashboard");
            $this->view->role = $user->getUserRole()->getRole()->name;
            $this->view->email = $user->getEmail();
        }

        $this->view->pick("errors/403");


    }


}
