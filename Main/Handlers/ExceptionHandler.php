<?php

namespace EO\Handlers;

use InvalidArgumentException;
use Pecee\Http\Request;
use Pecee\Http\Exceptions\MalformedUrlException;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;
use Pecee\SimpleRouter\Handlers\IExceptionHandler;
use EO\Handlers\Exceptions\DBQueryException;
use EO\Handlers\Exceptions\ValidationException;
use EO\Handlers\Exceptions\ResourceNotFoundException;
use EO\Handlers\Exceptions\AuthorizationException;
use EO\Handlers\Exceptions\MaintenanceException;
use EO\Handlers\Exceptions\RateLimitException;
use EO\Handlers\Exceptions\MailerException;

class ExceptionHandler implements IExceptionHandler
{
	/**
	 * @param Request $request
	 * @param \Exception $error
	 * @throws \Exception
	 */
	public function handleError(Request $request, $error): void
	{
		if(DEVELOPMENT) {
			if ($error instanceof MailerException) {
				return;
			}

			response()->json([
				'status' => 2,
				'message' => $error->getMessage(),
				'code' => $error->getCode(),
			]);
		}
		
		$request->error = 1;

		if(
			$error instanceof ResourceNotFoundException ||
			$error instanceof MalformedUrlException ||
			$error instanceof NotFoundHttpException
		) {
			$request->setRewriteCallback("\EO\Http\Controllers\ErrorsController@notFound");
			return;
		}

		if ($error instanceof AuthorizationException) {
			$request->setRewriteCallback("\EO\Http\Controllers\ErrorsController@forbidden");
			return;
		}

		if ($error instanceof MaintenanceException) {
			$request->setRewriteCallback("\EO\Http\Controllers\ErrorsController@maintenance");
			return;
		}

		if (
			$error instanceof InvalidArgumentException ||
			$error instanceof MailerException ||
			$error instanceof DBQueryException || 
			$error instanceof RateLimitException || 
			$error instanceof \Exception
		) {
			$request->setRewriteCallback("\EO\Http\Controllers\ErrorsController@serverError");
			return;
		}

		throw $error;

	}

}