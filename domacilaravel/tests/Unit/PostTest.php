<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test retrieving a post when it exists.
     *
     * @return void
     */


    /**
     * Test attempting to retrieve a non-existing post.
     *
     * @return void
     */
    public function testShowNonExistingPost()
    {

        $nonExistingUserId = 1000;
        $nonExistingPostId = 2000;

        $response = $this->get("/api/posts/{$nonExistingUserId}/{$nonExistingPostId}");


        $response->assertStatus(404);


        $response->assertJson([
            'message' => 'Data not found',
        ]);
    }



    public function test_store_endpoint()
    {

        Storage::fake('avatars');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'date_of_verification' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),

        ]);


        $response = $this->actingAs($user)->post('/api/posts', [
            'user_id' => $user->user_id,
            'content' => 'Test content',
            'image' => $file,
            'location' => 'Test location',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['message', 'Post']);
    }


    public function test_update_post()
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'date_of_verification' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ]);

        $postId = $this->getCustomPostId($user->user_id);


        $post = Post::create([
            'user_id' => $user->user_id,
            'post_id' => $postId,
            'content' => 'Original content',
            'location' => 'Original location',

        ]);

        $file = UploadedFile::fake()->image('updated-image.jpg');

        $response = $this->actingAs($user)->put("/api/posts/{$user->user_id}/{$post->post_id}", [
            'content' => 'Updated content',
            'image' => $file,
            'location' => 'Updated location',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'data']);

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'content' => 'Updated content',
            'location' => 'Updated location',

        ]);
    }

    private function getCustomPostId($userId)
    {
        $posts = Post::where('user_id', $userId)->get();
        $postId = $posts->max('post_id') + 1;
        return $postId;
    }

    public function test_delete_post()
    {

        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'date_of_verification' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ]);


        $postId = $this->getCustomPostId($user->user_id);
        $post = Post::create([
            'user_id' => $user->user_id,
            'post_id' => $postId,
            'content' => 'Original content',
            'location' => 'Original location',

        ]);


        $response = $this->actingAs($user)->delete("/api/posts/{$user->user_id}/{$post->post_id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Post successfully deleted']);


        $this->assertDatabaseMissing('posts', [
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
        ]);
    }
}
