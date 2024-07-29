<?php

namespace Main\Controllers;

class ErrorsController extends \Main\Controller {

    function __construct() {}

    function notFound() {
        $this->getLibrary("HttpHeaders")->setHeaderStatus(404);
        $this->setTemplate("/errors/notFound.php");
		return $this->render();
    }

    function forbidden() {
        $this->getLibrary("HttpHeaders")->setHeaderStatus(403);
        $this->setTemplate("/errors/forbidden.php");
		return $this->render();
    }

    function serverError() {
        $this->getLibrary("HttpHeaders")->setHeaderStatus(500);
        $this->setTemplate("/errors/serverError.php");
		return $this->render();
    }

}