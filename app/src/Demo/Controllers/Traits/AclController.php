<?php
/**
 * Created by Antony Repin
 * Date: 03.03.2017
 * Time: 22:57
 */

namespace Demo\Controllers\Traits;

use Demo\AccessControl;

trait AclController
{
    protected $userRole;

    protected function isAllowed($role, $resource, $access){
        return $this->acl->isAllowed($role, $resource, $access);
    }
}
