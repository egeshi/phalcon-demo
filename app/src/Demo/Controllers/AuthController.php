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
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $token;

    /**
     * Login response
     */
    public function loginAction()
    {

        if ($this->dispatcher->wasForwarded()) {
            // Forwarded from UserController::create()
            $user = $this->session->get("user");
            $this->email = $user->getEmail();
            $this->password = $user->getPassword();
            $this->token = $user->getToken();
        } else {

            $this->email = $this->request->getPost("email");
            $this->password = $this->request->getPost("password");

            $sha = sha1($this->password);
            $user = User::findFirst(["(email = :email:) AND password = :password:",
                    "bind" => [
                        "email" => $this->email,
                        "password" => $sha,
                    ]
                ]
            );

            $httpToken = $this->request->getHeader("Authorization");
        }

        if ($user->id > 0) {
            $this->acl->setUserId($user->id);
        } else {
            $this->response->setStatusCode(401, "Not Authorized")
                ->send();
            return;
        }

        // User found and authenticated by HTTP header
        if ($httpToken && $user->getToken() === $httpToken) {
            $this->response->setStatusCode(301, "/dashboard")
                ->send();
            return;
        }

        $this->session->set("user", $user);

        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $this->response
            ->setHeader("Authorization", $user->getToken())
            ->setJsonContent([
                "success" => true,
                "data" => [ "location" => "/dashboard" ],
            ])
            ->send();

    }

    /**
     * Reset password response
     */
    public function resetAction()
    {

        $this->setJsonData([])
            ->send();
    }
}
