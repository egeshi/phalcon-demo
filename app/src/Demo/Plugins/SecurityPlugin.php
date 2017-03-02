<?php
/**
 * Created by Antony Repin
 * Date: 02.03.2017
 * Time: 9:34
 */

namespace Demo\Plugins;

use Phalcon\Mvc\User\Plugin;

class SecurityPlugin extends Plugin
{
    public function beforeExecuteRoute(\Phalcon\Events\Event $event, \Phalcon\Mvc\Dispatcher $dispatcher)
    {
        //$auth = $this->session->get("auth");
    }
}
