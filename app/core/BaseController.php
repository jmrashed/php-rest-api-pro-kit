<?php

namespace App\Core;

use App\Core\Request;
use App\Core\Response;

abstract class BaseController
{
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Render a view.
     *
     * @param string $view The view file to render (e.g., 'home/index').
     * @param array $data Data to pass to the view.
     * @return void
     */
    protected function render(string $view, array $data = []): void
    {
        // This is a placeholder. In a real application, you would have a templating engine.
        // For now, we'll just simulate by including a file.
        extract($data);
        $viewPath = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';

        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            // Handle view not found error
            $this->response->setStatusCode(500)->json(['error' => 'View not found: ' . $view]);
        }
    }

    /**
     * Redirect to a different URL.
     *
     * @param string $url The URL to redirect to.
     * @param int $statusCode The HTTP status code for the redirect.
     * @return void
     */
    protected function redirect(string $url, int $statusCode = 302): void
    {
        $this->response->redirect($url, $statusCode);
    }

    /**
     * Send a JSON response.
     *
     * @param array $data The data to send as JSON.
     * @param int $statusCode The HTTP status code.
     * @return void
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        $this->response->setStatusCode($statusCode)->json($data);
    }
}