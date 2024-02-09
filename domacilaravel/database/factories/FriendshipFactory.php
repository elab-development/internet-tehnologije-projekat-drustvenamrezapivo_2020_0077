<?php

namespace Database\Factories;

use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Friendship;
use App\Models\User;

class FriendshipFactory extends Factory
{
    protected $model = Friendship::class;

    public function definition()
    {
        // Generate random user IDs for user1 and user2
        $user1Id = User::factory();
        $user2Id = User::factory();

        // Ensure user1_id is different from user2_id
        while ($user1Id === $user2Id) {
            $user2Id = User::factory();
        }

        return [
            'user1_id' => $user1Id,
            'user2_id' => $user2Id,
            'created_at' => $this->faker->dateTimeThisMonth,
            'updated_at' => $this->faker->dateTimeThisMonth,
        ];
    }
}