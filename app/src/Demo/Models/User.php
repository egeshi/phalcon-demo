<?php
/**
 * Created by Antony Repin
 * Date: 02.03.2017
 * Time: 7:33
 */

namespace Demo\Models;

use Phalcon\Mvc\Model;
use Demo\Models\UserRole;
use Demo\Exception\ApplicationException;

/**
 * Class User
 * @package Demo\Models
 *
 */
class User extends Model
{

    use \Demo\Models\Traits\TimestampableEntity;

    /**
     * Primary key
     *
     * @var int
     */
    private $id;

    /**
     * User email
     *
     * @var string
     */
    private $email;

    /**
     * User password
     *
     * @var string
     */
    private $password;

    /**
     * User auth token
     *
     * @var string
     */
    private $token;

    /**
     * Record creation date
     *
     * @var \DateTime
     */
    private $created;

    /**
     * Record update date
     *
     * @var \DateTime
     */
    private $updated;

    /**
     * Initiali entity class. only called once during the request, it’s intended to perform initializations that
     * apply for all instances of the model created within the application
     */
    public function initialize()
    {
        $this->setSource("users");
        $this->hasOne("id", "Demo\\Models\\UserRole", "user_id");
    }

    /**
     * Perform initialization tasks for every instance created
     */
    public function onConstruct()
    {
        $this->setUpdated();
        $this->setCreated();
    }

    /**
     * Tasks before entity is saved
     */
    public function beforeSave()
    {

        if (is_a($this->created, "DateTime")) {
            $this->created = $this->created->format(\DateTime::W3C);
        }

        if (is_a($this->updated, "DateTime")) {
            $this->updated = $this->created->format(\DateTime::W3C);
        }

    }

    /**
     * Prepare entity for display
     */
    public function afterFetch()
    {

    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $pw
     * @return $this
     */
    public function setPassword($pw)
    {
        $this->password = sha1($pw);

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param null $token
     * @return $this
     */
    public function setToken($token = null)
    {
        if (!$token) {
            $this->token = md5(microtime(true));
        } else {
            $this->token = $token;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Clear user token
     *
     * @return $this
     */
    public function deleteToken()
    {
        $this->token = null;

        return $this;
    }

    /**
     * @param null $parameters
     * @return Model\ResultsetInterface
     */
    public function getUserRole($parameters = null)
    {
        $role = $this->getRelated("Demo\\Models\\UserRole", $parameters);
        return $role;
    }

    /**
     * @param $roleName
     * @return bool
     * @throws ApplicationException
     */
    public function setUserRole($roleName)
    {

        $existingRoles = $roles = $this->getDI()->get("acl")->getRoles();

        foreach ($existingRoles as $role) {
            if ($roleName == $role->getName()) {
                $userRole = new UserRole();
                $userRole->setUserId($this->id)
                    ->setRole($role->getName())
                    ->save();

                return true;
            }
        }
    }

    /**
     * @throws ApplicationException
     */
    public function getRoleName()
    {
        $userRole = $this->getUserRole();

        $roleName = $userRole->getRole()->name;

        if (!$roleName) {
            throw new ApplicationException("Role '$roleName' not found", 500);
        }

        return $roleName;

    }




}
