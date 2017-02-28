<?php
/**
 * Created by Antony Repin
 * Date: 26.02.2017
 * Time: 19:58
 */

namespace Demo\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;

/**
 * Class IndexController
 *
 * @package Demo\Controllers
 */
class IndexController extends Controller
{
    public function indexAction()
    {
        echo "<h1>Hello there!</h1>";
    }
}
