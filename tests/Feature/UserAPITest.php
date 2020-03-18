<?php

namespace Tests\Unit;

use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserAPITest extends TestCase
{
    public function testUserCreation()
    {
        $response = $this->json('POST', '/api/register', [
            'name' => 'Demo User',
            'email' => Str::random(10) . '@demo.com',
            'password' => '12345',
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'success' => ['token', 'name']
        ]);
    }

    public function testUserLogin()
    {
        $response = $this->json('POST', '/api/login', [
            'email' => 'demo@demo.com',
            'password' => 'secret'
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'success' => ['token']
        ]);
    }
}
