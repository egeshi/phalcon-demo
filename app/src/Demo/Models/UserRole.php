<?php
/**
 * Created by Antony Repin
 * Date: 02.03.2017
 * Time: 10:50
 */

namespace Demo\Models;

use Demo\Exception\ApplicationException;
use Phalcon\Mvc\Model;

/**
 * Class UserRole
 * @package Demo\Models
 */
class UserRole extends Model
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $role_id;

    /**
     * @var int
     */
    protected $user_id;

    /**
     *
     */
    public function initialize()
    {
        $this->setSource("users_roles");

        $this->belongsTo("user_id", "Demo\\Models\\User", "id", [
            "foreignKey" => true
        ]);
        $this->belongsTo("role_id", "Demo\\Models\\Role", "id", [
            "foreignKey" => true
        ]);

    }

    /**
     * @param $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @param $role_id
     * @return $this
     */
    public function setRoleId($role_id)
    {
        $this->role_id = $role_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $roleName
     * @return $this
     * @throws ApplicationException
     */
    public function setRole($roleName)
    {

        $db = $this->getDI()->get("db");
        $sql = "SELECT `id` FROM `roles` WHERE `name` = ? LIMIT 1";
        $result = $db->query($sql, [$roleName]);
        $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);
        $roleId = $result->fetch()['id'];
        if (!$roleId) {
            throw new ApplicationException("Role '$roleName' not found", 500);
        }
        $this->setRoleId((int)$roleId);

        return $this;
    }

    /**
     * @return Model\ResultsetInterface
     */
    public function getRole(){
        return $this->getRelated("Demo\\Models\\Role");
    }

    /**
     * @return Model\ResultsetInterface
     */
    public function getUser(){
        return $this->getRelated("Demo\\Models\\User");
    }

}
