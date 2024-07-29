<?php

namespace Main\Controllers;

use Pecee\Controllers\IResourceController;

/**
 * Class DashboardController
 */
class DashboardController extends \Main\Controller
{

    /**
     * DashboardController constructor.
     */
    function __construct() {
        parent::__construct();
    }

    function index() {

        ob_start();
        echo "<pre class='bg-white text-dark'>";
        print_r($this->AuthService->user);
        echo "</pre>";
        $data = ob_get_contents();
        ob_clean();

        $this->setTemplate("/admin/dashboard/index.php");
        return $this->render($data);
    }

    function test() {
        return ["wa", "wo"];
    }

}