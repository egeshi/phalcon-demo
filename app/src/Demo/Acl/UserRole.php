<?php
/**
 * Created by Antony Repin
 * Date: 04.03.2017
 * Time: 0:09
 */

namespace Demo\Acl;

use Phalcon\Acl\RoleAware;

/**
 * Class UserRole
 * @package src\Demo\Acl
 */
class UserRole implements RoleAware
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $roleName;

    /**
     * UserRole constructor.
     * @param $id
     * @param $roleName
     */
    public function __construct($id, $roleName)
    {
        $this->id = $id;
        $this->roleName = $roleName;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRoleName()
    {
        return $this->roleName;
    }
}
