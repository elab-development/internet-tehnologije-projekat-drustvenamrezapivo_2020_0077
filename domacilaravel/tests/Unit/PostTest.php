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
   

    /**
     * Test attempting to retrieve a non-existing post.
     *
     * @return void
     */
    public function testShowNonExistingPost()
    {
        // Use arbitrary user and post IDs that don't exist in the database
        $nonExistingUserId = 1000;
        $nonExistingPostId = 2000;

        // Make a GET request to the show endpoint with non-existing IDs
        $response = $this->get("/api/posts/{$nonExistingUserId}/{$nonExistingPostId}");

        // Assert the response status is 404 (Not Found)
        $response->assertStatus(404);

        // Assert the response structure or any other assertions you need
        $response->assertJson([
            'message' => 'Data not found',
        ]);
    }

    /* public function test_show_post()
{
    // Create a user
    $user = User::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'date_of_verification' => now(),
        'password' => bcrypt('password'),
        'remember_token' => Str::random(10),
    ]);

    // Create a post
    $post = Post::create([
        'user_id' => $user->user_id,
        'post_id' => 1, // You might need to adjust this based on your logic
        'content' => 'Test content',
        'image_path' => 'test_image.jpg', // Replace with the actual image path
        'location' => 'Test location',
    ]);

    // Hit the show endpoint
    $response = $this->get("/api/posts/{$user->user_id}/{$post->post_id}");

    dump($response->json());

    // Assert the response
    $response->assertStatus(200);

    // Assert the JSON structure
    $response->assertJsonStructure([
        'user_id',
        'post_id',
        'content',
        'image_path',
        'location',
        'created_at',
        'updated_at',
    ]);

    // Ensure the returned data matches the created post
    $response->assertJson([
        'user_id' => $user->user_id,
        'post_id' => $post->post_id,
        'content' => $post->content,
        'image_path' => $post->image_path,
        'location' => $post->location,
    ]);
} */

public function testShowPost()
{
    // Create a user using the factory
    $user = User::factory()->create();

    // Create a post using the factory
    $post = Post::factory()->create([
        'user_id' => $user->user_id,
    ]);

    // Make a GET request to the show endpoint
    $response = $this->get("/api/posts/{$user->user_id}/{$post->post_id}");

    // Assert the response status is 200 (OK)
    $response->assertStatus(200);

  /*  // Assert the JSON structure
    $response->assertJsonStructure([
    //    'user_id',
        'post_id',
        'content',
        'image_path',
        'created_at',
        'updated_at',
    ]); */

    // Assert the returned data matches the created post
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
            'password' => bcrypt('password'), // Make sure to hash the password
            'remember_token' => Str::random(10),
            // Add other fields as needed
        ]);
      //  $image = UploadedFile::fake()->image('test-image.jpg');

        $response = $this->actingAs($user)->post('/api/posts', [
            'user_id' => $user->user_id,
            'content' => 'Test content',
            'image' => $file,
            'location' => 'Test location',
        ]);

        $response->assertStatus(201); // Check if the response status is 201 (Created)
        $response->assertJsonStructure(['message', 'Post']); // Check the structure of the JSON response

       
    }

    
    public function test_update_post()
    {
        Storage::fake('public'); // Use the disk name you configured in your filesystems.php

        // Manually create a user
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'date_of_verification' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ]);

        $postId = $this->getCustomPostId($user->user_id);

        // Manually create a post for the user
        $post = Post::create([
            'user_id' => $user->user_id,
            'post_id' => $postId,
            'content' => 'Original content',
            'location' => 'Original location',
            // Add other fields as needed
        ]);

        $file = UploadedFile::fake()->image('updated-image.jpg');

        $response = $this->actingAs($user)->put("/api/posts/{$user->user_id}/{$post->post_id}", [
            'content' => 'Updated content',
            'image' => $file,
            'location' => 'Updated location',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'data']);

        // Optionally, you can assert that the post is updated in the database
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'content' => 'Updated content',
            'location' => 'Updated location',
            // Add other fields that you expect to be updated
        ]);

        // Optionally, you can assert that the new image is stored
      
    }

    private function getCustomPostId($userId)
    {
        $posts = Post::where('user_id', $userId)->get();
        $postId = $posts->max('post_id') + 1;
        return $postId;
    }

   /* public function test_delete_post()
    {
        // Manually create a user
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'date_of_verification' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ]);
    
        // Manually create a post for the user with a specific post ID
        $postId = $this->getCustomPostId($user->user_id);
        $post = Post::create([
            'user_id' => $user->user_id,
            'post_id' => $postId,
            'content' => 'Original content',
            'location' => 'Original location',
            // Add other fields as needed
        ]);
    
        // Mock the unlink function to simulate successful file deletion
        $this->mock(Filesystem::class, function ($mock) {
            $mock->shouldReceive('unlink')->andReturn(true);
        });
    
        // Delete the post
        $response = $this->actingAs($user)->delete("/api/posts/{$user->user_id}/{$post->post_id}");
    
        // Assert the response
        $response->assertStatus(200); // Check if the response status is 200 (OK)
        $response->assertJson(['message' => 'Post successfully deleted']); // Check the JSON response
    
        // Ensure the post is actually deleted from the database
        $this->assertDatabaseMissing('posts', [
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
        ]);
    }*/

    public function testPostsOfFriends()
    {
        // Assuming you have necessary data in the database
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
                        // ... other expected fields
                    ],
                ],
                'message' => 'Posts successfully retrieved',
            ]);
    }
    
    public function testPostsOfEnemies()
{
    // Assuming you have necessary data in the database
    $user = User::factory()->create();
    $friend = User::factory()->create();
    Friendship::factory()->create(['user1_id' => $user->user_id, 'user2_id' => $friend->user_id]);
    
    // Creating posts
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
                    // ... other expected fields
                ],
            ],
            'message' => 'Posts successfully retrieved',
        ]);
}

    public function testPostsOfProfile()
    {
        // Assuming you have necessary data in the database
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
                        // ... other expected fields
                    ],
                ],
                'message' => 'Posts successfully retrieved',
            ]);
    }


}