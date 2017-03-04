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
     * @var string
     */
    private $name;

    /**
     *
     */
    public function initialize()
    {
        $this->setSource("roles");
        $this->hasMany("id", "Demo\\Models\\UserRole", "role_id");
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


}
