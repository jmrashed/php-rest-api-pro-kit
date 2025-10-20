<?php

try {
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->run();
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Internal Server Error']);
}