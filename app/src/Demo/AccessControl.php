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
use Phalcon\Di\InjectionAwareInterface;

/**
 * Class Acl
 * @package Demo
 */
class AccessControl implements InjectionAwareInterface
{

    /**
     * @var AclList
     */
    private $acl;

    /**
     * @var ModelResource
     */
    private $users;

    private $roles;

    /**
     * For future implementation with ModelResource and UserRole
     *
     * @var int
     */
    private $userId;

    private $di;

    /**
     * AccessControl constructor.
     * @param \Phalcon\Di\FactoryDefault $di
     */
    public function __construct(\Phalcon\Di\FactoryDefault $di)
    {
        $this->di = $di;
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

        $sql = "SELECT * FROM `roles`";
        $db = $this->di->get("db");
        $result = $db->query($sql);
        $result->setFetchMode(\Phalcon\Db::FETCH_ASSOC);

        while ($role = $result->fetch()) {
            $this->acl->addRole(new Role($role['name'], $role['description']));
        }

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

    /**
     * Sets the dependency injector
     *
     * @param \Phalcon\DiInterface $dependencyInjector
     * @return \stdClass
     */
    public function setDI(\Phalcon\DiInterface $dependencyInjector)
    {
        $this->di = $dependencyInjector;
        return $this;
    }

    /**
     * Returns the internal dependency injector
     *
     * @return \Phalcon\DiInterface
     */
    public function getDI()
    {
        return $this->di;
    }
}
