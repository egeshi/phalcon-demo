<?php
/**
 * Created by Antony Repin
 * Date: 03.03.2017
 * Time: 22:28
 */

namespace Demo;

use Demo\Acl\ModelResource;
use Demo\Acl\UserRole;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Acl\Resource;
use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Paginator\Adapter\Model;

/**
 * Class Acl
 * @package Demo
 */
class AccessControl
{

    /**
     * @var AclList
     */
    private $acl;

    /**
     * @var ModelResource
     */
    private $users;

    /**
     * For future implementation with ModelResource and UserRole
     *
     * @var int
     */
    private $userId;

    /**
     * Acl constructor.
     */
    public function __construct()
    {
        $this->roles = new \stdClass();
        $this->acl = new AclList();
        $this->acl->setDefaultAction(Acl::DENY);
        $this->setRoles()
            ->setResources()
            ->setAccessLevels();
    }

    /**
     * @return AclList
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @return ModelResource
     */
    public function getUsersResource()
    {
        return $this->users;
    }

    /**
     * Object containig role references
     *
     * @return \stdClass
     */
    public function getRoles()
    {
        return $this->acl->getRoles();
    }

    /**
     * @param string $roleName
     * @param string $resourceName
     * @param string $access
     * @return bool
     */
    public function isAllowed($roleName, $resourceName, $access)
    {
        return $this->acl->isAllowed($roleName, $resourceName, $access);
    }

    /**
     * For future implementation with ModelResource and UserRole
     *
     * @param int $id
     * @return $this
     */
    public function setUserId($id){
        $this->userId = $id;

        return $this;
    }

    /**
     * Set up user roles
     *
     * @return $this
     */
    private function setRoles()
    {
        $this->acl->addRole(new Role("guest", "Guests"));
        $this->acl->addRole(new Role("staff", "Registered staff"));
        $this->acl->addRole(new Role("customer", "Registered customers"));

        return $this;

    }

    /**
     * Set up access resources
     *
     * @return $this
     */
    private function setResources()
    {

        $this->acl->addResource(
            new Resource("Index", "Dashboard action ACL resource"),
            ["reset", "register", "login", "dashboard"]
        );

        return $this;
    }

    /**
     * @return $this
     */
    private function setAccessLevels()
    {
        $this->acl->allow("staff", "Index", "reset");
        $this->acl->allow("staff", "Index", "dashboard");
        $this->acl->allow("customer", "Index", "dashboard");
        $this->acl->allow("guest", "Index", "register");
        $this->acl->allow("guest", "Index", "login");

        return $this;
    }
}
