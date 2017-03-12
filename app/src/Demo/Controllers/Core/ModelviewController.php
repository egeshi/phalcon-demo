<?php
/**
 * Created by Antony Repin
 * Date: 28.02.2017
 * Time: 10:09
 */

namespace Demo\Controllers\Core;

use Phalcon\Mvc\Controller;

/**
 * Class ControllerBase
 * @package Demo\Controllers
 */
class ModelviewController extends Controller
{

    use \Demo\Controllers\Traits\AclController;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $token;

    /**
     * Add common variables and assets to layout
     */
    public function initialize()
    {
        $this->setAppAssets();
        $this->setTitle();

        $this->view->urlService = $this->di->get("url");

        $this->user = $this->session->get("user");
        $this->token = $this->request->getHeader("Authorization");;
    }

    /**
     * Add assets to the view
     */
    public function setAppAssets()
    {
        $this->assets->addCss("assets/css/app.css");
        $this->assets->addJs("assets/js/app.js");
    }

    /**
     * Set view title
     */
    public function setTitle($title = null)
    {

        $this->view->title = "Phalcon Demo Project";

        if (!is_null($title)) {
            $this->view->title = $title;
        }
    }

}
