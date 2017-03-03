<?php
/**
 * Created by Antony Repin
 * Date: 01.03.2017
 * Time: 16:05
 */

namespace Demo\Controllers;

use Demo\Controllers\Core\ApiController;
use Demo\Exception\ApplicationException;
use Demo\Controllers\Core\ModelviewController;
use Phalcon\Mvc\View;
use Demo\Models\User;

/**
 * Class AuthController
 * @package Demo\Controllers
 */
class AuthController extends ModelviewController
{
    /**
     * Login response
     */
    public function loginAction()
    {

        if ($this->dispatcher->wasForwarded()){
            // Forwarded from ApiController::create()
            $user = $this->session->get("user");
            $email = $user->getEmail();
            $password = $user->getPassword();
            $token = $user->getToken();
        } else {

            $email = $this->request->getPost("email");
            $password = $this->request->getPost("password");

            $sha = sha1($password);
            $user = User::findFirst(["(email = :email:) AND password = :password:",
                    "bind" => [
                        "email" => $email,
                        "password" => $sha,
                    ]
                ]
            );

            $token = $this->request->getHeader("Authorization");
        }

        if (!$user->getId()) {
            $this->response->setStatusCode(401, "Not Authorized")
                ->send();
            return;
        }

        if ($token && $user->getToken() === $token) {
            $this->dispatcher->forward(
                [
                    "controller" => "Demo\\Controllers\\Index",
                    "action" => "inner",
                ]
            );
        }

        // Tokens do not match
        $now = new \DateTime();
        $user->setToken(sha1($now->format(\DateTime::W3C)));
        $this->session->set("user", $user);

        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $this->response->setJsonContent([
            "success" => true,
            "data" => $user
        ])
            ->setHeader("Authorization", $user->getToken())
            ->send();

    }

    /**
     * Reset password resonse
     */
    public function resetAction()
    {

        $this->setJsonData([])
            ->send();
    }
}
