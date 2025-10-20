<?php

namespace App\Middleware;

use App\Core\Response;
use App\Core\Cache;

class RateLimitMiddleware extends Middleware
{
    private $maxRequests = 60;
    private $timeWindow = 3600;

    public function handle($request, $next)
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $key = "rate_limit_{$ip}";
        
        $requests = Cache::get($key) ?? 0;
        
        if ($requests >= $this->maxRequests) {
            return Response::json(['error' => 'Rate limit exceeded'], 429);
        }
        
        Cache::set($key, $requests + 1, $this->timeWindow);
        
        return $next($request);
    }
}