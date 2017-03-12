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
     * @var string
     */
    protected $role;

    /**
     * Create user [/api/user/create]
     */
    public function createAction()
    {

        if ($this->request->getMethod() == "OPTIONS") {
            $this->setJsonData([]);
            $this->response->send();
            return;
        }

        if ($this->request->getMethod() == "POST") {

            /* TODO: Add validation */

            $this->email = $this->request->getPost("email");
            $this->password = $this->request->getPost("password");
            $this->role = $this->request->getPost("role");

            if (!$this->role) {
                $this->setJsonData([
                    "success" => false,
                    "data" => [
                        "validation" => "Role is missing"
                    ],
                ]);
                $this->response
                    ->setStatusCode(403)
                    ->send();
                return;
            }

            $found = User::findFirst(["(email = :email:)",
                    "bind" => ["email" => $this->email]
                ]
            );

            if ($found) {
                $this->setJsonData(new \ArrayObject([
                    "success" => false,
                    "data" => ["validation" => "User with that email already exists"],
                ]));
                $this->response->send();

            } else {

                $now = new \DateTime();
                $token = base64_encode($now->format(\DateTime::W3C));

                $user = new User();
                $user->setEmail($this->email)
                    ->setPassword(sha1($this->password))
                    ->setToken($token);

                try {
                    if (!$user->create()) {
                        $messages = $user->getMessages();
                        $this->jsonError(404, null, $messages);
                        return;
                    }

                } catch (\Exception $e) {
                    throw new ApplicationException("User cannot be saved", 500);
                }

                $user->setUserRole($this->role);

                $this->session->set("user", $user);

                $this->dispatcher->forward([
                    "controller" => "Demo\\Controllers\\Auth",
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
    public function editAction()
    {
        if ($this->request->isPut()){
            $data = $this->request->getPut();
            
            foreach ($data as $prop){
                // TODO Add update
            }
        }

    }
    
    

    /**
     * Delete entities
     *
     * @var int $id
     */
    public function deleteAction()
    {
        if ($this->request->isDelete()){

            $data = $this->request->get("id");

            // TODO Add action code and response
        }
    }

    /**
     * Read entity
     *
     * @var int $id
     */
    public function getAction()
    {
        if ($this->request->isGet()){

            $data = $this->request->get("id");

            // TODO Add action code and response
        }
    }

    /**
     * Get entities list as ArrayCollection
     */
    public function collectionAction()
    {
        if ($this->request->isHead()){

            $users = User::find();

            // TODO Add response
        }
    }
}
