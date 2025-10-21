<?php

namespace App\Api\Versioning;

use App\Core\Router;
use App\Core\Request;

class VersionedRouter extends Router
{
    private $versionedRoutes = [];

    public function addVersionedRoute(string $version, string $method, string $uri, array $handler, array $middleware = []): void
    {
        $this->versionedRoutes[$version][] = [
            'method' => $method,
            'uri' => $uri,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public function dispatch(Request $request)
    {
        $version = $request->getApiVersion() ?? 'v1';
        
        // Try versioned routes first
        if (isset($this->versionedRoutes[$version])) {
            foreach ($this->versionedRoutes[$version] as $route) {
                if ($this->matchRoute($request, $route)) {
                    return $this->executeRoute($request, $route);
                }
            }
        }

        // Fallback to regular routes
        parent::dispatch($request);
    }

    private function matchRoute(Request $request, array $route): bool
    {
        $requestUri = $request->getUri();
        $requestMethod = $request->getMethod();
        
        // Remove version from URI for matching
        $cleanUri = preg_replace('/\/v\d+/', '', $requestUri);
        $pattern = $this->buildRegexPattern($route['uri']);
        
        return preg_match($pattern, $cleanUri) && $route['method'] === $requestMethod;
    }

    private function executeRoute(Request $request, array $route): void
    {
        $requestUri = $request->getUri();
        $cleanUri = preg_replace('/\/v\d+/', '', $requestUri);
        $pattern = $this->buildRegexPattern($route['uri']);
        
        if (preg_match($pattern, $cleanUri, $matches)) {
            array_shift($matches);
            
            $handler = $route['handler'];
            $middleware = $route['middleware'];
            
            $this->runMiddleware($request, $middleware, function (Request $request) use ($handler, $matches) {
                if (is_array($handler) && count($handler) === 2) {
                    list($controllerName, $methodName) = $handler;
                    $controller = new $controllerName();
                    $response = call_user_func_array([$controller, $methodName], array_merge([$request], $matches));
                    if ($response) $response->send();
                }
            });
        }
    }
}