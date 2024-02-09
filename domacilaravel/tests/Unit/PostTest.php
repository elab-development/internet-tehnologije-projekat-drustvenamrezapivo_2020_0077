<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PostTest extends TestCase
{
    use RefreshDatabase; 
    /**
     * Test retrieving a post when it exists.
     *
     * @return void
     */
   

  
     /*
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

  

public function testShowPost()
{
   
    $user = User::factory()->create();

    
    $post = Post::factory()->create([
        'user_id' => $user->user_id,
    ]);


    $response = $this->get("/api/posts/{$user->user_id}/{$post->post_id}");

  
    $response->assertStatus(200);


 
    $response->assertJson([
        'message' => 'Post successfully retrieved',
    'Post' => [
        'user_id' => $user->user_id,
        'post_id' => $post->post_id,
        'content' => $post->content,
        'image_path' => $post->image_path,
        'created_at' => $post->created_at->toISOString(),
        'updated_at' => $post->updated_at->toISOString(),
        'location' => $post->location,
    ],
    ]);
}

    public function test_store_post()
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

 

    public function testPostsOfFriends()
    {
       
        $user = User::factory()->create();
        $friend = User::factory()->create();
        Friendship::factory()->create(['user1_id' => $user->user_id, 'user2_id' => $friend->user_id]);
        $post = Post::factory()->create(['user_id' => $friend->user_id]);

        $response = $this->get("/api/postsOfFriends/{$user->user_id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'posts' => [
                    [
                        'user_id' => $friend->user_id,
                        'post_id' => $post->post_id,
                        'content' => $post->content,
                      
                    ],
                ],
                'message' => 'Posts successfully retrieved',
            ]);
    }
    
    public function testPostsOfEnemies()
{
  
    $user = User::factory()->create();
    $friend = User::factory()->create();
    Friendship::factory()->create(['user1_id' => $user->user_id, 'user2_id' => $friend->user_id]);
    
  
    $userPost = Post::factory()->create(['user_id' => $user->user_id]);
    $enemyPost = Post::factory()->create();

    $response = $this->get("/api/postsOfEnemies/{$user->user_id}");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'posts' => [
                [
                    'user_id' => $enemyPost->user_id,
                    'post_id' => $enemyPost->post_id,
                    'content' => $enemyPost->content,
                   
                ],
            ],
            'message' => 'Posts successfully retrieved',
        ]);
}

    public function testPostsOfProfile()
    {
       
        $user = User::factory()->create();
        $userPost = Post::factory()->create(['user_id' => $user->user_id]);

        $response = $this->get("/api/postsOfProfile/{$user->user_id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'posts' => [
                    [
                        'user_id' => $user->user_id,
                        'post_id' => $userPost->post_id,
                        'content' => $userPost->content,
                       
                    ],
                ],
                'message' => 'Posts successfully retrieved',
            ]);
    }


}