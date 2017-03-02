<?php
/**
 * Created by Antony Repin
 * Date: 01.03.2017
 * Time: 16:05
 */

namespace Demo\Controllers;

use Demo\Exception\ApplicationException;
use Demo\Controllers\Core\ApiController;
use Phalcon\Mvc\View;
use Demo\Models\User;

/**
 * Class AuthController
 * @package Demo\Controllers
 */
class AuthController extends ApiController
{

    protected $email;

    protected $password;

    protected $token;

    /**
     * Listener
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @return bool
     */
    /*public function beforeExecuteRoute(\Phalcon\Mvc\Dispatcher $dispatcher)
    {
        parent::beforeExecuteRoute($dispatcher);
    }*/

    /**
     * Login response
     */
    public function loginAction(){

        $this->email    = $this->request->getPost("email");
        $this->password = $this->request->getPost("password");

        $sha = sha1($this->password);
        $user = User::findFirst( [ "(email = :email:) AND password = :password:",
                "bind" => [
                    "email"    => $this->email,
                    "password" => $sha,
                ]
            ]
        );

        if ($user) {
            $this->_registerSession($user);

            $this->setJsonData($user)
                ->setResponseStatus()
                ->send()
            ;
            return;
        }

        $this->jsonError(401, "Not Authorized");

    }

    /**
     * Reset password resonse
     */
    public function resetAction(){

        $this->setJsonData([])
            ->send();
    }

    /**
     * Register session
     *
     * @param User $user
     */
    private function _registerSession(User $user)
    {
        $this->session->set(
            "auth",
            [
                "id"   => $user->id,
                "name" => $user->name,
            ]
        );
    }
}
