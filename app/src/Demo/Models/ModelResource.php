<?php
/**
 * Created by Antony Repin
 * Date: 03.03.2017
 * Time: 22:25
 */

namespace Demo\Models;

use Phalcon\Acl\ResourceAware;

/**
 * Class ModelResource
 * @package Demo\Models
 */
class ModelResource implements ResourceAware
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $resourceName;

    /**
     * @var int
     */
    protected $userId;

    /**
     * ModelResource constructor.
     * @param int $id
     * @param string $resourceName
     * @param int $userId
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
     * @return string
     */
    public function getResourceName()
    {
        return $this->resourceName;
    }
}
