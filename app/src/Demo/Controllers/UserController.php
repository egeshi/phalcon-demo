<?php
/**
 * Created by Antony Repin
 * Date: 02.03.2017
 * Time: 23:16
 */

namespace Demo\Controllers;

use Demo\Controllers\Core\ApiController;
use Demo\Exception\ApplicationException;
use Demo\Models\User;
use Phalcon\Mvc\Controller\BindModelInterface;

/**
 * Class UserController
 * @package Demo\Controllers
 */
class UserController extends ApiController implements BindModelInterface
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $token;

    /**
     * Create user [/api/user/create]
     */
    public function createAction()
    {

        if ($this->request->getMethod() == "OPTIONS"){
            $this->setJsonData([]);
            $this->response->send();
            return;
        }

        if($this->request->getMethod() == "POST"){

            $this->email = $this->request->getPost("email");
            $this->password = $this->request->getPost("password");

            $found = User::findFirst(["(email = :email:)",
                    "bind" => ["email" => $this->email]
                ]
            );

            if ($found) {
                $this->setJsonData(new \ArrayObject([
                    success => false,
                    "message" => "User with that email already exists",
                ]));
                $this->response->send();

            } else {

                $now = new \DateTime();
                $token = base64_encode($now->format(\DateTime::W3C));

                $user = new User();
                $user->setEmail($this->email);
                $user->setPassword(sha1($this->password));
                $user->setToken($token);

                try {

                    $saved = $user->create();

                    if (!$saved) {
                        $messages = $user->getMessages();
                        $this->jsonError(404, null, $messages);
                    }

                } catch (\Exception $e) {
                    throw new ApplicationException("User cannot be saved", 500);
                }

                $this->session->set("user",[
                    "id" => $user->id,
                    "email" => $this->email,
                    "token" => $token,
                ]);

                $this->dispatcher->forward([
                    "controller"=> "Demo\\Controllers\\Auth",
                    "action" => "login"
                ]);
            }
        }

    }

    /**
     * Get model name associated with controller
     *
     * @return mixed
     */
    public static function getModelName()
    {
        return User::class;
    }

    /**
     * Update entities
     *
     * @var int $id
     */
    protected function editAction()
    {
        // TODO: Implement editAction() method.
    }

    /**
     * Delete entities
     *
     * @var int $id
     */
    protected function deleteAction()
    {
        // TODO: Implement deleteAction() method.
    }

    /**
     * Read entity
     *
     * @var int $id
     */
    protected function getAction()
    {
        // TODO: Implement getAction() method.
    }

    /**
     * Get entities list as ArrayCollection
     */
    protected function collectionAction()
    {
        // TODO: Implement index() method.
    }
}
