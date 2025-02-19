<?php

namespace EO\Http\Controllers;

use EO\Factories\Factory as Factory;
use EO\Http\BaseController as Controller;
use EO\View as View;
use EO\Http\Response\HttpHeaders as HttpHeaders;

class ErrorsController extends Controller
{
	function notFound()
	{
		response()->httpCode(404);
		return View::set("/errors/notFound.php", false);
	}

	function forbidden()
	{
		HttpHeaders::setResponseCode(403, "Access denied! You do not have enough permission to change the data.");
		return View::set("/errors/forbidden.php", false);
	}

	function serverError()
	{
		HttpHeaders::setResponseCode(500);
		return View::set("/errors/serverError.php", false);
	}

	function maintenance()
	{
		response()->httpCode(500);
		return View::set("/errors/maintenance.php", false);
	}

}