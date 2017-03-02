<?php
/**
 * Created by Antony Repin
 * Date: 02.03.2017
 * Time: 10:46
 */

namespace Demo\Models;

use Phalcon\Mvc\Model;

/**
 * Class Role
 * @package src\Demo\Models
 */
class Role extends Model
{
    /**
     * Primary key
     *
     * @var int
     */
    private $id;

    /**
     * @var \Demo\Models\User
     */
    private $user_id;

    /**
     * @var \Demo\Models\Role
     */
    private $role_id;

    /**
     *
     */
    public function initialize()
    {
        $this->setSource("roles");
        $this->hasMany( "id", "UserRole", "role_id" );
    }

    public function getId(){
        return $this->id;
    }

    public function setRoleId($id){
        $this->role_id = $id;

        return $this;
    }

    public function getRoleId(){
        return $this->role_id;
    }

    public function setUserId($id){
        $this->user_id = $id;

        return $this;
    }

    public function getUser(){
        return $this->user_id;
    }
}
