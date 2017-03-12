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

            $user = User::findFirst(["(email = :email:) AND password = :password:",
                    "bind" => [
                        "email" => $this->email,
                        "password" => sha1($this->password),
                    ]
                ]
            );

            $this->session->set("user", $user);
        }

        $httpToken = $this->request->getHeader("Authorization");

        if ($user->id > 0) {
            $this->acl->setUserId($user->id); //For future implementation with ModelResource and UserRole
        } else {
            $this->response->setStatusCode(401, "Not Authorized")
                ->send();
            return;
        }

        // User found and authenticated by HTTP header
        if ($httpToken && $user->getToken() === $httpToken) {
            $this->response
                ->setHeader("Authorization", $user->getToken())
                ->setJsonContent([
                    "success" => true,
                    "data" => ["location" => "/dashboard"],
                ])
                ->send();
            return;
        }

        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $this->response
            ->setHeader("Authorization", $user->getToken())
            ->setJsonContent([
                "success" => true,
                "data" => ["location" => "/dashboard"],
            ])
            ->send();

    }

    /**
     * Reset password response
     */
    public function resetAction()
    {
        $this->email = $this->request->getPost("email");

        $user = User::findFirst("email = '{$this->email}'");

        if (!$user || !($user instanceof User)) {
            $this->response
                ->setJsonContent([
                    "success" => false,
                    "data" => ["message" => "User not found"],
                ])
                ->send();
            return;
        }

        $oldPasswd = $user->getPassword();
        $passwd = uniqid();
        $user->update([
            "password" => sha1($passwd),
        ]);

        $this->response
            ->setJsonContent([
                "success" => true,
                "data" => ["message" => "Password was changed from '$oldPasswd' to '$passwd'. Save it somewhere."],
            ])
            ->send();


    }

    /**
     * TODO: Add logout
     */
    public function logoutAction()
    {
        
    }
}
