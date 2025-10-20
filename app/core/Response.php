<?php

namespace App\Core;

class Response
{
    private $statusCode = 200;
    private $headers = [];
    private $body;

    public function setStatusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function withJson($data)
    {
        $this->addHeader('Content-Type', 'application/json');
        $this->body = json_encode($data);
        return $this;
    }

    public function send()
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->body;
        exit;
    }

    public static function json($data, $statusCode = 200)
    {
        $response = new self();
        return $response->setStatusCode($statusCode)->withJson($data);
    }
}