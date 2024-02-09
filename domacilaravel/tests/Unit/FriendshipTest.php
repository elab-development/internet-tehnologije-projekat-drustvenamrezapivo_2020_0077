<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class FriendshipTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

   

    public function test_store_friendship()
    {
        // Create two users using the factory
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Set up the request data
        $requestData = [
            'user1_id' => $user1->user_id,
            'user2_id' => $user2->user_id,
        ];

        // Make a POST request to your store endpoint
        $response = $this->actingAs($user1)->post('/api/friendships', $requestData);

        // Assert the response status
        $response->assertStatus(201);

        // Assert the JSON structure
        $response->assertJsonStructure([
            'message',
            'Friend 1' => [
                'user1_id',
                'user2_id',
                'created_at',
                'updated_at',
            ],
            'Friend 2' => [
                'user1_id',
                'user2_id',
                'created_at',
                'updated_at',
            ],
        ]);

        // Optionally, you can assert that the friendships are stored in the database
        $this->assertDatabaseHas('friendships', [
            'user1_id' => $user1->user_id,
            'user2_id' => $user2->user_id,
        ]);

        $this->assertDatabaseHas('friendships', [
            'user1_id' => $user2->user_id,
            'user2_id' => $user1->user_id,
        ]);
    }

    public function test_show_friends()
    {
        // Create a user using the factory
        $user = User::factory()->create();

        // Create some friendships for the user
        $friend1 = User::factory()->create();
        $friend2 = User::factory()->create();

        Friendship::create([
            'user1_id' => $user->user_id,
            'user2_id' => $friend1->user_id,
        ]);

        Friendship::create([
            'user1_id' => $user->user_id,
            'user2_id' => $friend2->user_id,
        ]);

        // Make a GET request to your show endpoint
        $response = $this->get("/api/friendships/{$user->user_id}");

        // Assert the response status
        $response->assertStatus(200);

        // Assert the JSON structure
        $response->assertJsonStructure([
            'message',
            'friends' => [
                '*' => [
                    'user_id',
                    'name',
                    'email',
                    'date_of_verification',
                    'created_at',
                    'updated_at',
                    'picture',
                    'about',
                ],
            ],
        ]);

        // Optionally, you can assert the content of the response
     /*   $response->assertJson([
            'message' => 'Friends retrieved successfully',
            'friends' => [
                [
                    'user_id' => $friend1->user_id,
                    'name' => $friend1->name,
                    // Add other fields as needed
                ],
                [
                    'user_id' => $friend2->user_id,
                    'name' => $friend2->name,
                    // Add other fields as needed
                ],
            ],
        ]);*/
    }

    public function test_destroy_friendship()
    {
        // Create two users and establish a friendship
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Friendship::create([
            'user1_id' => $user1->user_id,
            'user2_id' => $user2->user_id,
        ]);

        // Make a DELETE request to your destroy endpoint
        $response = $this->actingAs($user1)->delete("/api/friendships/{$user1->user_id}/{$user2->user_id}");

        // Assert the response status
        $response->assertStatus(200);

        // Assert the JSON structure
        $response->assertJsonStructure([
            'message',
        ]);

        // Optionally, you can assert the content of the response
        $response->assertJson([
            'message' => 'Friendship suscesfully deleted',
        ]);

        // Optionally, you can assert that the friendship is deleted from the database
        $this->assertDatabaseMissing('friendships', [
            'user1_id' => $user1->user_id,
            'user2_id' => $user2->user_id,
        ]);

        // Also, assert the inverse relationship is deleted
        $this->assertDatabaseMissing('friendships', [
            'user1_id' => $user2->user_id,
            'user2_id' => $user1->user_id,
        ]);
    }


}
