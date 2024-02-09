<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;



class CommentTest extends TestCase
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

    public function testShowComment()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a post for the user
        $post = Post::factory()->create(['user_id' => $user->user_id]);

        // Create a comment for the post
        $comment = Comment::factory()->create([
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'comment_id' => 1, // Adjust the comment_id based on your logic
        ]);

        // Hit the show endpoint
        $response = $this->get("/api/comments/{$user->user_id}/{$post->post_id}/{$comment->comment_id}");

        // Assert the response
        $response->assertStatus(200);

        // Assert the JSON structure
        $response->assertJsonStructure([
            'comment' => [
                'user_id',
                'post_id',
                'comment_id',
                'commentator_id',
                'content',
                'created_at',
                'updated_at',
            ],
            'success',
            'message',
        ]);

        // Ensure the returned data matches the created comment
        $response->assertJson([
            'comment' => [
                'user_id' => $user->user_id,
                'post_id' => $post->post_id,
                'comment_id' => $comment->comment_id,
                'commentator_id' => $comment->commentator_id,
                'content' => $comment->content,
            ],
            'success' => true,
            'message' => 'Comment successfully retrieved',
        ]);
    }


    public function testStoreComment()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a post
        $post = Post::factory()->create(['user_id' => $user->user_id]);

        // Data for the request
        $data = [
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'commentator_id' => $user->user_id, // Assuming commentator is the same as the user in this test
            'content' => $this->faker->sentence,
        ];

        // Make a POST request to store the comment
        $response = $this->actingAs($user)->post('/api/comments', $data);

        // Assert the response
        $response->assertStatus(201); // Check if the response status is 201 (Created)

        // Assert the JSON structure
      /*  $response->assertJsonStructure([
          //  'message',
            'comment' => [
                'user_id',
                'post_id',
                'comment_id',
                'commentator_id',
                'content',
                'created_at',
                'updated_at',
            ],
        ]); */

        // Optionally, you can assert that the comment is stored in the database
        $this->assertDatabaseHas('comments', $data);
    }

    public function test_update_comment()
    {
        // Manually create a user
        $user = User::factory()->create();
    
        // Manually create a post for the user
        $post = Post::factory()->create([
            'user_id' => $user->user_id,
        ]);
    
        // Manually create a comment for the post
        $comment = Comment::factory()->create([
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            // Add other fields as needed
        ]);
    
        $response = $this->actingAs($user)->put("/api/comments/{$user->user_id}/{$post->post_id}/{$comment->comment_id}", [
            'content' => 'Updated content',
            // Add other fields that you want to update
        ]);
    
        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'data']);
    
        // Optionally, you can assert that the comment is updated in the database
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'comment_id' => $comment->comment_id,
            'content' => 'Updated content',
            // Add other fields that you expect to be updated
        ]);
    }

    public function test_destroy_comment()
{
    // Manually create a user
    $user = User::factory()->create();

    // Manually create a post for the user
    $post = Post::factory()->create([
        'user_id' => $user->user_id,
    ]);

    // Manually create a comment for the post
    $comment = Comment::factory()->create([
        'user_id' => $user->user_id,
        'post_id' => $post->post_id,
    ]);

    $response = $this->actingAs($user)->delete("/api/comments/{$user->user_id}/{$post->post_id}/{$comment->comment_id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'comment suscesfully deleted']);

    // Optionally, you can assert that the comment is deleted from the database
    $this->assertDatabaseMissing('comments', [
        'user_id' => $user->user_id,
        'post_id' => $post->post_id,
        'comment_id' => $comment->comment_id,
    ]);
}
}

