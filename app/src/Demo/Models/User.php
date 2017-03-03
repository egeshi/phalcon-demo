<?php
/**
 * Created by Antony Repin
 * Date: 02.03.2017
 * Time: 7:33
 */

namespace Demo\Models;

use Demo\Exception\ApplicationException;
use Phalcon\Forms\Element\Date;
use Phalcon\Mvc\Model;
use Demo\Models\Traits\TimestampableEntity;
use JMS\Serializer\Annotation;

/**
 * Class User
 * @package Demo\Models
 * @ExclusionPolicy("none")
 *
 */
class User extends Model
{
    
    use TimestampableEntity;
    
    /**
     * Primary key
     *
     * @var int
     */
    private $id;

    /**
     * User email
     *
     * @var string
     */
    private $email;

    /**
     * User password
     *
     * @var string
     * @Exclude
     */
    private $password;

    /**
     * User auth token
     *
     * @var string
     */
    private $token;

    /**
     * Record creation date
     *
     * @var \DateTime
     */
    private $created;

    /**
     * Record update date
     *
     * @var \DateTime
     */
    private $updated;

    /**
     * Initiali entity class. only called once during the request, it’s intended to perform initializations that
     * apply for all instances of the model created within the application
     */
    public function initialize()
    {
        $this->setSource("users");
        $this->hasMany("id", "UserRole", "user_id");
    }

    /**
     * Perform initialization tasks for every instance created
     */
    public function onConstruct(){
        $this->setUpdated();
        $this->setCreated();
    }

    /**
     * Tasks before entity is saved
     */
    public function beforeSave(){

        if (is_a($this->created, "DateTime")){
            $this->created = $this->created->format(\DateTime::W3C);
        }

        if (is_a($this->updated, "DateTime")){
            $this->updated = $this->created->format(\DateTime::W3C);
        }

    }

    /**
     * Prepare entity for display
     */
    public function afterFetch()
    {
        $this->password = null;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $pw
     * @return $this
     */
    public function setPassword($pw)
    {
        $this->password = $pw;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }



    
}