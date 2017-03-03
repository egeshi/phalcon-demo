<?php
/**
 * Created by Antony Repin
 * Date: 01.03.2017
 * Time: 16:06
 */

namespace Demo\Controllers\Core;

use Demo\Exception\ApplicationException;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;


/**
 * Class ControllerCrud
 * @package Demo\Controllers
 */
abstract class ApiController extends Controller
{

    const STATUS_SUCCESS = "success";
    const STATUS_ERROR = "error";

    /**
     * @var \ArrayObject
     */
    protected $jsonData;

    /**
     * Called prior to any action
     */
    public function initialize()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $this->response->setHeader("Access-Control-Allow-Methods", "POST, GET, PUT, DELETE, OPTIONS");
        $this->response->setHeader("Access-Control-Allow-Origin", "*");
        $this->response->setHeader("Access-Control-Allow-Headers", "origin, content-type, accept, authorization");
        $this->response->setHeader("Content-Type", "application/json");
        $this->jsonData = new \stdClass();
    }

    /**
     * Called just after the controller object is constructed
     */
    public function onConstruct(){

    }

    /**
     * Create entity
     */
    protected abstract function createAction();

    /**
     * Update entity
     *
     * @var int $id
     */
    protected abstract function editAction();

    /**
     * Delete entity
     *
     * @var int $id
     */
    protected abstract function deleteAction();

    /**
     * Read entity
     *
     * @var int $id
     */
    protected abstract function getAction();

    /**
     * Collection
     * @return ArrayCollection
     */
    protected abstract function collectionAction();

    /**
     * Show JSON error response
     *
     * @param int $statusCode
     * @param string $message
     * @param array $errors //Array of \Phalcon\Mvc\Model\Message objects
     * @return null|false
     */
    protected function jsonError($statusCode, $message = null, $errors = null)
    {

        $data = [
            "success"=>false,
            "errors"=>[]
        ];

        foreach ($errors as $idx=>$err) {
            $data['errors'][$idx]['field'] = $err->getField();
            $data['errors'][$idx]['message'] = $err->getMessage();
        }

        $this->response->setJsonCOntent($data)
            ->setStatusCode($statusCode, $message)
            ->send();
        return false;

    }

    /**
     * @param array|object $data
     * @return $this
     * @throws ApplicationException
     */
    protected function setJsonData($data)
    {

        if (is_array($data)){
            $this->jsonData = $data;
        } else {
            if (is_object($data) && !method_exists($data, "getDI")){
                $d = [];
                foreach ($data as $k=>$v){
                    $d[$k] = $v;
                }
                $this->jsonData = $d;
            } else {
                $this->jsonData = $data;
            }
        }

        $this->response->setJsonContent($this->jsonData);

        return $this;
    }

    /**
     * @param bool $success
     * @return $this
     */
    protected function setResponseStatus($success)
    {
        $this->jsonData->success = $success;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getjsonData()
    {
        return $this->jsonData;
    }
}
