<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Like;
use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;


class LikeTest extends TestCase
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

    public function test_store_like()
    {
        // Manually create a user
        $user = User::factory()->create();

        // Manually create a post for the user
        $post = Post::factory()->create([
            'user_id' => $user->user_id,
        ]);

        // Simulate a request to store a like
        $response = $this->actingAs($user)->post("/api/likes", [
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'liker_id' => $user->user_id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'Post']);

        // Optionally, you can assert that the like is stored in the database
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'liker_id' => $user->user_id,
        ]);
    }

    public function test_show_like()
    {
        // Manually create a user
        $user = User::factory()->create();

        // Manually create a post for the user
        $post = Post::factory()->create([
            'user_id' => $user->user_id,
        ]);

        // Manually create a like for the post
        $like = Like::factory()->create([
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'liker_id' => $user->user_id,
        ]);

        // Simulate a request to show the like
        $response = $this->get("/api/likes/{$user->user_id}/{$post->post_id}/{$user->user_id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);

        // Optionally, you can assert the content of the response
    /*    $response->assertJson([
            'data' => [
                'user_id' => $user->user_id,
                'post_id' => $post->post_id,
                'liker' => [
                    'user_id' => $user->user_id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'post' => [
                    'location' => $post->location,
                    'content' => $post->content,
                    'image_path' => $post->image_path,
                ],
              //  'created_at' => $like->created_at->toIso8601String(), // Format timestamp to ISO8601
            ],
        ]);*/

        
    }

    public function test_destroy_like()
{
    // Use factories to create necessary records
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->user_id]);
    $liker = User::factory()->create();
    $like = Like::factory()->create([
        'user_id' => $user->user_id,
        'post_id' => $post->post_id,
        'liker_id' => $liker->user_id,
    ]);

    // Ensure the like record exists before deleting
    $this->assertDatabaseHas('likes', [
        'user_id' => $user->user_id,
        'post_id' => $post->post_id,
        'liker_id' => $liker->user_id,
    ]);

    $response = $this->actingAs($user)->delete("/api/likes/{$user->user_id}/{$post->post_id}/{$liker->user_id}");

    $response->assertStatus(200)
        ->assertJsonStructure(['message', 'likepost']);

    // Ensure the like record is deleted after calling the destroy method
    $this->assertDatabaseMissing('likes', [
        'user_id' => $user->user_id,
        'post_id' => $post->post_id,
        'liker_id' => $liker->user_id,
    ]);
}
}
