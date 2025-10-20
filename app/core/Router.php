<?php

namespace App\Core;

class Router
{
    private $routes = [];
    private $middleware = [];
    private $routeGroups = [];

    public function addRoute($method, $uri, $handler, $middleware = [])
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
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

    public function dispatch(Request $request, Response $response)
    {
        $requestUri = $request->getUri();
        $requestMethod = $request->getMethod();

        foreach ($this->routes as $route) {
            $pattern = $this->buildRegexPattern($route['uri']);
            if (preg_match($pattern, $requestUri, $matches) && $route['method'] === $requestMethod) {
                array_shift($matches); // Remove the full match

                $handler = $route['handler'];
                $middleware = $this->resolveMiddleware($route['middleware']);

                $this->runMiddleware($request, $response, $middleware, function (Request $request) use ($handler, $matches, $response) {
                    if (is_callable($handler)) {
                        call_user_func_array($handler, array_merge([$request, $response], $matches));
                    } elseif (is_array($handler) && count($handler) === 2) {
                        list($controllerName, $methodName) = $handler;
                        $controller = new $controllerName();
                        call_user_func_array([$controller, $methodName], array_merge([$request, $response], $matches));
                    }
                });
                return;
            }
        }

        Response::json(['status' => 'error', 'message' => 'Not Found'], 404);
    }

    private function buildRegexPattern(string $uri): string
    {
        // Convert URI parameters like {id} to regex capture groups
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $uri);
        return "#^" . $pattern . "$#";
    }

    private function runMiddleware(Request $request, Response $response, array $middleware, callable $coreHandler)
    {
        $chain = array_reverse($middleware);

        $next = function (Request $req) use (&$chain, $response, $coreHandler) {
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