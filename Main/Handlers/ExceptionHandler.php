<?php

namespace Main\Handlers;

use Pecee\Http\Request;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\Handlers\IExceptionHandler;
use Main\App\Controller\ErrorsController as ErrorsController;

class ExceptionHandler implements IExceptionHandler
{
	/**
	 * @param Request $request
	 * @param \Exception $error
	 * @throws \Exception
	 */
	public function handleError(Request $request, \Exception $error): void
	{

		/* You can use the exception handler to format errors depending on the request and type. */

		if ($error instanceof \Exception) {
			/* response()->json([
				'status' => 2,
				'message' => "Oops! Something went wrong! Your are trying to upload a file that is not allowed to be uploaded or your doing something unexpected. Please stop doing something that is not allowed."
			]); */

			response()->json([
				'status' => 2,
				'message' => $error->getMessage(),
				'code' => $error->getCode(),
			]);
		}

		/* The router will throw the NotFoundHttpException on 404 */
		if ($error instanceof NotFoundHttpException) {

			/*
			 * Render your own custom 404-view, rewrite the request to another route,
			 * or simply return the $request object to ignore the error and continue on rendering the route.
			 *
			 * The code below will make the router render our page.notfound route.
			 */

			$request->setRewriteCallback("ErrorsController@notFound");
			return;

		}

		throw $error;

	}

}