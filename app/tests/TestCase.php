<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Setup test environment
    }

    protected function tearDown(): void
    {
        // Cleanup after tests
        parent::tearDown();
    }

    protected function makeRequest($method, $uri, $data = [])
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;
        
        if (!empty($data)) {
            $_POST = $data;
        }
        
        // Simulate request handling
        ob_start();
        // Your request handling logic here
        $output = ob_get_clean();
        
        return json_decode($output, true);
    }
}