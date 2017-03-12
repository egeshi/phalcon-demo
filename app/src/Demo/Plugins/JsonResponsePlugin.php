<?php
/**
 * Created by Antony Repin
 * Date: 02.03.2017
 * Time: 7:45
 */

namespace Demo\Plugins;

use Phalcon\Mvc\User\Plugin;

/**
 * Plugin is serving JSON reponse
 *
 * Class JsonResponsePlugin
 * @package src\Demo\Plugins
 */
class JsonResponsePlugin extends Plugin
{
    /**
     * @param \Phalcon\Events\Event $event
     * @param \Phalcon\Mvc\Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute(\Phalcon\Events\Event $event, \Phalcon\Mvc\Dispatcher $dispatcher)
    {
        
        switch ($dispatcher->getControllerName()){

            case "Demo\\Controllers\\Auth":

                $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);

                $message = new \stdClass();
                $message->error = "Request is not AJAX";

                if(!$this->request->isAjax()){
                    $event->stop();
                    $this->response->setContent(json_encode($message))
                        ->setStatusCode(401, "Not Authorized")
                    ->send();
                    return false;
                }
                break;

        }


    }
}
