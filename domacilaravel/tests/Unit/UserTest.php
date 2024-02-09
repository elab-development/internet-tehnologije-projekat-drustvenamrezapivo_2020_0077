<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Friendship;
use App\Models\Like;
use App\Models\Comment;

class UserTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function testRegister()
{
    $userData = [
        'username' => 'testuser',
        'email' => 'testuser@example.com',
        'password' => 'password123',
    ];

    $response = $this->json('POST', '/api/register', $userData);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'data' => [
                'name' => 'testuser',
                'email' => 'testuser@example.com',
            ],
            'access_token' => true,
            'token_type' => 'Bearer',
            'message' => 'Successful registration',
        ]);
}

public function testLogin()
{
    $user = User::factory()->create([
        'email' => 'testuser@example.com',
        'password' => bcrypt('password123'),
    ]);

    $loginData = [
        'email' => 'testuser@example.com',
        'password' => 'password123',
    ];

    $response = $this->json('POST', '/api/login', $loginData);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'user' => [
                'email' => 'testuser@example.com',
                // ... other expected fields
            ],
            'access_token' => true,
            'token_type' => 'Bearer',
            'message' => 'User successfully logged in',
        ]);
}

public function testLogout()
{
    $user = User::factory()->create();

    $token = $user->createToken('auth_token')->plainTextToken;

    $headers = [
        'Authorization' => 'Bearer ' . $token,
    ];

    $response = $this->json('POST', '/api/logout', [], $headers);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'user succesfully logged out',
        ]);
}
}