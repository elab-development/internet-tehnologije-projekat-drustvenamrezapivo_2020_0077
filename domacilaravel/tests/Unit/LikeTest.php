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
      
        $user = User::factory()->create();

       
        $post = Post::factory()->create([
            'user_id' => $user->user_id,
        ]);

       
        $response = $this->actingAs($user)->post("/api/likes", [
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'liker_id' => $user->user_id,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'Post']);

       
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'liker_id' => $user->user_id,
        ]);
    }

    public function test_show_like()
    {
        
        $user = User::factory()->create();

        
        $post = Post::factory()->create([
            'user_id' => $user->user_id,
        ]);

      
        $like = Like::factory()->create([
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'liker_id' => $user->user_id,
        ]);

       
        $response = $this->get("/api/likes/{$user->user_id}/{$post->post_id}/{$user->user_id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);

    
        
    }

    public function test_destroy_like()
{
    
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->user_id]);
    $liker = User::factory()->create();
    $like = Like::factory()->create([
        'user_id' => $user->user_id,
        'post_id' => $post->post_id,
        'liker_id' => $liker->user_id,
    ]);

   
    $this->assertDatabaseHas('likes', [
        'user_id' => $user->user_id,
        'post_id' => $post->post_id,
        'liker_id' => $liker->user_id,
    ]);

    $response = $this->actingAs($user)->delete("/api/likes/{$user->user_id}/{$post->post_id}/{$liker->user_id}");

    $response->assertStatus(200)
        ->assertJsonStructure(['message', 'likepost']);

   
    $this->assertDatabaseMissing('likes', [
        'user_id' => $user->user_id,
        'post_id' => $post->post_id,
        'liker_id' => $liker->user_id,
    ]);
}
}
