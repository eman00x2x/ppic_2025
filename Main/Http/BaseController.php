<?php

namespace EO\Http;

use Pecee\Http\Request;
use Pecee\SimpleRouter\Route\RouteUrl;

use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\AuthorizationException;
use EO\Http\HttpHeaders;
use EO\Auth\Auth;
use EO\View;

class BaseController 
{
	function setResponseCode(int $status_code, ?string $description = null) 
	{
		HttpHeaders::setResponseCode($status_code, $description);
		/* response()->httpCode($status_code); */
	}

	function authorize(string $action, ?array $data = null): void 
	{
		if (!Auth::allows($action, Auth::user()->account, $data)) {
			throw new AuthorizationException("Unauthorized access. You don't have permission to see this page.");
		}
	}

	function handleMessageResponse(string $message, string $type = "success", ?int $status = 1) 
	{
		return View::set("JSON")->bind(data: [
			"status" => $status,
			"type" => $type,
			"message" => View::getHtmlResponseMessage(message: $message, type: $type)
		]);
	}

}
