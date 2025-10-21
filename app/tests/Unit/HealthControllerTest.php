<?php

namespace App\Tests\Unit;

use App\Tests\TestCase;
use App\Controllers\HealthController;
use App\Core\Response;

class HealthControllerTest extends TestCase
{
    public function testCheckReturnsHealthyStatus()
    {
        $controller = new HealthController();
        $response = $controller->check();

        $this->assertInstanceOf(Response::class, $response);
        $body = json_decode($response->getBody(), true);

        $this->assertIsArray($body);
        $this->assertArrayHasKey('api', $body);
        $this->assertEquals('healthy', $body['api']);
        $this->assertArrayHasKey('timestamp', $body);
        $this->assertArrayHasKey('database', $body);
        $this->assertArrayHasKey('memory_usage', $body);
        $this->assertArrayHasKey('disk_space', $body);
    }
}