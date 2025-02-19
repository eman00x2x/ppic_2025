<?php

namespace EO\Http;

use InvalidArgumentException;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\LimiterInterface;

class RateLimiter
{
    private $limiters;

    public function __construct(array $limiters)
    {
		$this->limiters = $limiters;
    }

    public function getLimiter(string $name): LimiterInterface
    {
        if (!isset($this->limiters[$name])) {
            throw new InvalidArgumentException("Rate limiter $name not found.");
        }

        return $this->limiters[$name];
    }
}