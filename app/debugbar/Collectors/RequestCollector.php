<?php

namespace App\DebugBar\Collectors;

use App\DebugBar\BaseCollector;

class RequestCollector extends BaseCollector
{
    public function getName(): string
    {
        return 'request';
    }

    public function collect(): array
    {
        return [
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
            'uri' => $_SERVER['REQUEST_URI'] ?? '',
            'headers' => $this->getHeaders(),
            'get' => $_GET,
            'post' => $_POST,
            'server' => array_filter($_SERVER, fn($k) => !in_array($k, ['HTTP_AUTHORIZATION']), ARRAY_FILTER_USE_KEY)
        ];
    }

    private function getHeaders(): array
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
}