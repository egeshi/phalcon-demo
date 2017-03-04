<?php
/**
 * Created by Antony Repin
 * Date: 04.03.2017
 * Time: 0:07
 */

namespace Demo\Acl;

use Phalcon\Acl\ResourceAware;

/**
 * Class ModelResource
 * @package src\Demo\Acl
 */
class ModelResource implements ResourceAware
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $resourceName;

    /**
     * @var int
     */
    protected $userId;

    /**
     * ModelResource constructor.
     * @param $id
     * @param $resourceName
     * @param $userId
     */
    public function __construct($id, $resourceName, $userId)
    {
        $this->id = $id;
        $this->resourceName = $resourceName;
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getResourceName()
    {
        return $this->resourceName;
    }
}
