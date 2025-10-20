<?php

namespace App\Core;

class Router
{
    private $routes = [];
    private $middleware = [];
    private $routeGroups = [];

    public function addRoute($method, $uri, $handler, $middleware = [])
    {
        $fullUri = $uri;
        foreach ($this->routeGroups as $group) {
            $fullUri = $group['prefix'] . $fullUri;
        }
        
        $this->routes[] = [
            'method' => $method,
            'uri' => $fullUri,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    public function group(string $prefix, array $middleware, callable $callback)
    {
        $this->routeGroups[] = ['prefix' => $prefix, 'middleware' => $middleware];
        $callback($this);
        array_pop($this->routeGroups); // Remove the last group after callback
    }

    private function resolveMiddleware(array $routeMiddleware): array
    {
        $middlewares = [];
        foreach ($this->routeGroups as $group) {
            $middlewares = array_merge($middlewares, $group['middleware']);
        }
        $middlewares = array_merge($middlewares, $routeMiddleware);
        return $middlewares;
    }

    public function dispatch(Request $request)
    {
        $requestUri = $request->getUri();
        $requestMethod = $request->getMethod();

        foreach ($this->routes as $route) {
            $pattern = $this->buildRegexPattern($route['uri']);
            if (preg_match($pattern, $requestUri, $matches) && $route['method'] === $requestMethod) {
                array_shift($matches); // Remove the full match

                $handler = $route['handler'];
                $middleware = $this->resolveMiddleware($route['middleware']);

                $this->runMiddleware($request, $middleware, function (Request $request) use ($handler, $matches) {
                    if (is_callable($handler)) {
                        $response = call_user_func_array($handler, array_merge([$request], $matches));
                    } elseif (is_array($handler) && count($handler) === 2) {
                        list($controllerName, $methodName) = $handler;
                        $controller = new $controllerName();
                        $response = call_user_func_array([$controller, $methodName], array_merge([$request], $matches));
                    }
                    if ($response) $response->send();
                });
                return;
            }
        }

        Response::json(['status' => 'error', 'message' => 'Not Found'], 404)->send();
    }

    private function buildRegexPattern(string $uri): string
    {
        // Convert URI parameters like {id} to regex capture groups
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $uri);
        return "#^" . $pattern . "$#";
    }

    private function runMiddleware(Request $request, array $middleware, callable $coreHandler)
    {
        $chain = array_reverse($middleware);

        $next = function (Request $req) use (&$chain, $coreHandler) {
            if (empty($chain)) {
                return $coreHandler($req);
            }

            $currentMiddlewareClass = array_pop($chain);
            $currentMiddleware = new $currentMiddlewareClass();
            return $currentMiddleware->handle($req, $next);
        };

        $next($request);
    }
}