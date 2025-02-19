<?php

namespace EO\Http\Middleware;

use Symfony\Component\RateLimiter\Policy\FixedWindowLimiter;
use Symfony\Component\RateLimiter\Storage\InMemoryStorage;
use Symfony\Component\RateLimiter\Storage\CacheStorage;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request as Request;
use EO\Handlers\CacheHandler;
use EO\Handlers\Exceptions\RateLimitException;
use EO\Http\RateLimiter;
use EO\Facades\LoggerFacade as Logger;
use DateInterval;

/**
 * Class RateLimiterMiddleware
 */
class RateLimiterMiddleware implements IMiddleware 
{
	/**
	 * @param Request $request
	 */
	public function handle(Request $request): void 
    {
		$cache = new CacheHandler();
		$cache->setRenewalInterval(0);
		$file_system_storage = new CacheStorage($cache->getAdapterInstance());

		$rate_limiter = new RateLimiter([
			"eo_rate_limiter" => new FixedWindowLimiter(
				'eo_rate_limiter',         /** unique name for the limiter */
				100,                        /** maximum 10 requests */
				new DateInterval('PT60S'),                        /** per 60 seconds */
				$file_system_storage
			)
		]);

		$limiter = $rate_limiter->getLimiter('eo_rate_limiter');
        $limit = $limiter->consume(1); // consume 1 token/request

        if (!$limit->isAccepted()) { 
			
			Logger::log("critical", "Too many requests", [
					"route" => $request->getUrl()->getPath(),
					"main_message" => "Too many requests",
					"context" => [],
					"data" => []
				]
			);

            // If the limit is reached, return an error
            response()->httpCode(429);
			$request->setRewriteCallback("\EO\Http\Controllers\ErrorsController@serverError");
			return;
        }
	}
}