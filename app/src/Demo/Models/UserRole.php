<?php
/**
 * Created by Antony Repin
 * Date: 02.03.2017
 * Time: 10:50
 */

namespace Demo\Models;

use Phalcon\Mvc\Model;

/**
 * Class UsersRoles
 * @package Demo\Models
 */
class UsersRoles extends Model
{
    /**
     *  
     */
    public function initialize()
    {
        $this->belongsTo("user_id", "Demo\\Models\\User", "id", [
            "foreignKey" => true
        ]);
        $this->belongsTo("role_id", "Demo\\Models\\Role", "id", [
            "foreignKey" => true
        ]);
    }
}
