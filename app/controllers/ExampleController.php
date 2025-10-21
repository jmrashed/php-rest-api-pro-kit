<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Helpers\Logger;

class ExampleController extends Controller
{
    public function index(Request $request, Response $response)
    {
        Logger::info('ExampleController index method accessed.', ['user_id' => 123, 'ip_address' => $request->ip()]);
        return $response->json(['message' => 'Example index response']);
    }

    public function show(Request $request, Response $response, $id)
    {
        Logger::warning('ExampleController show method accessed with ID.', ['id' => $id, 'user_id' => 123]);
        return $response->json(['message' => "Showing example with ID: {$id}"]);
    }

    public function store(Request $request, Response $response)
    {
        $data = $request->json();
        if (empty($data['name'])) {
            Logger::error('Attempted to store example with missing name.', ['request_data' => $data]);
            return $response->json(['error' => 'Name is required'], 400);
        }
        Logger::info('New example stored successfully.', ['data' => $data]);
        return $response->json(['message' => 'Example stored', 'data' => $data], 201);
    }
}