<?php

namespace Database\Factories;

// database/factories/PostFactory.php

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class PostFactory extends Factory
{
    protected $model = Post::class;


    public function definition()
    {

        $fakeImagePath = 'images/' . UploadedFile::fake()->image('test-image.jpg')->name;

        return [
            'user_id' => User::factory(), // Use the UserFactory to create a related user
            'post_id' => $this->faker->unique()->randomNumber(), // You can adjust this based on your logic
            'content' => $this->faker->paragraph,
            'image_path' => $fakeImagePath,
            'created_at' => now(),
            'updated_at' => now(),
            'location' => $this->faker->city
        ];
    }
}
