<?php

namespace App\Api\Versioning;

use App\Core\Request;
use App\Core\Response;

class ApiVersionMiddleware
{
    public function handle(Request $request, callable $next)
    {
        $version = $this->extractVersion($request);
        $request->setApiVersion($version);
        
        return $next($request);
    }

    private function extractVersion(Request $request): string
    {
        // Check Accept header first (e.g., application/vnd.api+json;version=1)
        $acceptHeader = $request->getHeader('Accept');
        if ($acceptHeader && preg_match('/version=(\d+)/', $acceptHeader, $matches)) {
            return 'v' . $matches[1];
        }

        // Check X-API-Version header
        $versionHeader = $request->getHeader('X-API-Version');
        if ($versionHeader) {
            return 'v' . ltrim($versionHeader, 'v');
        }

        // Check URI path (e.g., /api/v1/users)
        $uri = $request->getUri();
        if (preg_match('/\/api\/v(\d+)\//', $uri, $matches)) {
            return 'v' . $matches[1];
        }

        // Default to v1
        return 'v1';
    }
}