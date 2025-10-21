<?php

namespace App\Core;

use App\DebugBar\DebugBar;

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

    public function getBody()
    {
        return $this->body;
    }

    public function send()
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        
        $debugBar = DebugBar::getInstance();
        if ($debugBar->isEnabled()) {
            $debugData = $debugBar->collect();
            
            if (isset($this->headers['Content-Type']) && strpos($this->headers['Content-Type'], 'application/json') !== false) {
                // For JSON responses, add debug data as header
                header('X-Debugbar-Data: ' . base64_encode(json_encode($debugData)));
            } else {
                // For HTML responses, inject toolbar
                $output = ob_get_clean();
                if (strpos($output, '</body>') !== false) {
                    ob_start();
                    $debugData = $debugData;
                    include __DIR__ . '/../debugbar/Views/toolbar.php';
                    $toolbar = ob_get_clean();
                    $output = str_replace('</body>', $toolbar . '</body>', $output);
                }
                echo $output;
                exit;
            }
        }
        
        echo $this->body;
        exit;
    }

    public static function json($data, $statusCode = 200)
    {
        $response = new self();
        return $response->setStatusCode($statusCode)->withJson($data);
    }

    public static function success($message = 'Success', $data = [], $statusCode = 200)
    {
        return (new self())->setStatusCode($statusCode)->withJson([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function error($message = 'Error', $statusCode = 500, $errors = [])
    {
        return (new self())->setStatusCode($statusCode)->withJson([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ]);
    }
}