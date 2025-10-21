<?php

namespace App\Core;

class Request
{
    private $method;
    private $uri;
    private $headers;
    private $body;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->headers = function_exists('getallheaders') ? getallheaders() : $this->getAllHeaders();
        $this->body = file_get_contents('php://input');
    }

    private function getAllHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace('_', '-', substr($key, 5));
                $headers[$header] = $value;
            }
        }
        return $headers;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getJsonBody()
    {
        return json_decode($this->body, true);
    }

    public function getHeader($name)
    {
        return $this->headers[$name] ?? null;
    }

    private $user;

    public function setUser(array $user)
    {
        $this->user = $user;
    }

    public function user()
    {
        return $this->user;
    }

    public function json(): array
    {
        return json_decode($this->body, true) ?? [];
    }
}