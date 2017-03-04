<?php
/**
 * Created by Antony Repin
 * Date: 02.03.2017
 * Time: 10:50
 */

namespace Demo\Models;

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
     * @var string
     */
    protected $roleName;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
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

    public function getId()
    {
        return $this->id;
    }

    /**
     *
     */
    public function initialize()
    {
        $this->setSource("users");

        $this->belongsTo("user_id", "Demo\\Models\\User", "id", [
            "foreignKey" => true
        ]);
        $this->belongsTo("role_id", "Demo\\Models\\Role", "id", [
            "foreignKey" => true
        ]);

    }

    /**
     * @return mixed
     */
    public function getRoleId()
    {
        return $this->role_id;
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


}
