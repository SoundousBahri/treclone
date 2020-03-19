<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Support\Str;
use Tests\TestCase;

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
        $email= Str::random(10) . '@demo.com';
        $password= '12345';
        User::create([
            'name' => 'Demo User',
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $response = $this->json('POST', '/api/login', [
            'email' => $email,
            'password' => $password
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'success' => ['token','name']
        ]);
    }
}
