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
        
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        
        $requestData = [
            'user1_id' => $user1->user_id,
            'user2_id' => $user2->user_id,
        ];

      
        $response = $this->actingAs($user1)->post('/api/friendships', $requestData);

       
        $response->assertStatus(201);

       
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
        
        $user = User::factory()->create();

        
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

      
        $response = $this->get("/api/friendships/{$user->user_id}");

       
        $response->assertStatus(200);

      
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

 
    }

    public function test_destroy_friendship()
    {
      
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Friendship::create([
            'user1_id' => $user1->user_id,
            'user2_id' => $user2->user_id,
        ]);

      
        $response = $this->actingAs($user1)->delete("/api/friendships/{$user1->user_id}/{$user2->user_id}");

      
        $response->assertStatus(200);

       
        $response->assertJsonStructure([
            'message',
        ]);

      
        $response->assertJson([
            'message' => 'Friendship suscesfully deleted',
        ]);

       
        $this->assertDatabaseMissing('friendships', [
            'user1_id' => $user1->user_id,
            'user2_id' => $user2->user_id,
        ]);

        
        $this->assertDatabaseMissing('friendships', [
            'user1_id' => $user2->user_id,
            'user2_id' => $user1->user_id,
        ]);
    }


}
