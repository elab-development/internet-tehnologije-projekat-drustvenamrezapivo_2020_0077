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
       
        $user = User::factory()->create();

        
        $post = Post::factory()->create(['user_id' => $user->user_id]);

       
        $comment = Comment::factory()->create([
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'comment_id' => 1,
        ]);

       
        $response = $this->get("/api/comments/{$user->user_id}/{$post->post_id}/{$comment->comment_id}");

       
        $response->assertStatus(200);

     
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
       
        $user = User::factory()->create();

       
        $post = Post::factory()->create(['user_id' => $user->user_id]);

      
        $data = [
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'commentator_id' => $user->user_id, 
            'content' => $this->faker->sentence,
        ];

        $response = $this->actingAs($user)->post('/api/comments', $data);

      
        $response->assertStatus(201);


      
        $this->assertDatabaseHas('comments', $data);
    }

    public function test_update_comment()
    {
       
        $user = User::factory()->create();
    
      
        $post = Post::factory()->create([
            'user_id' => $user->user_id,
        ]);
    
      
        $comment = Comment::factory()->create([
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
           
        ]);
    
        $response = $this->actingAs($user)->put("/api/comments/{$user->user_id}/{$post->post_id}/{$comment->comment_id}", [
            'content' => 'Updated content',
          
        ]);
    
        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'data']);
    
      
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'comment_id' => $comment->comment_id,
            'content' => 'Updated content',
          
        ]);
    }

    public function test_destroy_comment()
{
   
    $user = User::factory()->create();

   
    $post = Post::factory()->create([
        'user_id' => $user->user_id,
    ]);

    
    $comment = Comment::factory()->create([
        'user_id' => $user->user_id,
        'post_id' => $post->post_id,
    ]);

    $response = $this->actingAs($user)->delete("/api/comments/{$user->user_id}/{$post->post_id}/{$comment->comment_id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'comment suscesfully deleted']);

   
    $this->assertDatabaseMissing('comments', [
        'user_id' => $user->user_id,
        'post_id' => $post->post_id,
        'comment_id' => $comment->comment_id,
    ]);
}
}

