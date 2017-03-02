<?php
/**
 * Created by Antony Repin
 * Date: 02.03.2017
 * Time: 23:16
 */

namespace Demo\Controllers;

use Demo\Controllers\Core\ApiController;
use Demo\Models\User;

/**
 * Class UserController
 * @package Demo\Controllers
 */
class UserController extends ApiController
{

    protected $email;

    protected $password;

    /**
     * Create user
     */
    public function createAction()
    {

        $this->email = $this->request->getPost("email");
        $this->password = $this->request->getPost("password");

        $found = User::findFirst(["(email = :email:)",
                "bind" => ["email" => $this->email]
            ]
        );

        if ($found) {

            $this->setJsonData(new \ArrayObject([
                "result" => ApiController::STATUS_ERROR,
                "message" => "User with that email already exists",
            ]));

        } else {

            $user = new User([
                "email" => $this->email,
                "password" => sha1($this->password)
            ]);

            try {

                $saved = $user->create();

                if (!$saved) {
                    $messages = $user->getMessages();
                    $this->jsonError(404, null, $messages);
                }

            } catch (\Exception $e) {
                die(__FILE__ . '::' . __LINE__);
            }

            $this->setJsonData(new \ArrayObject([
                "result" => ApiController::STATUS_SUCCESS,
                "data" => $user,
            ]));
        }

        $this->response->send();
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
