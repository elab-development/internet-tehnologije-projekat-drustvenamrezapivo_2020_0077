<?php

namespace Database\Seeders;

use App\Models\Friendship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FriendshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Friendship::create(['user1_id'=>1,'user2_id'=>2]);
        Friendship::create(['user1_id'=>2,'user2_id'=>1]);
        Friendship::create(['user1_id'=>1,'user2_id'=>3]);
        Friendship::create(['user1_id'=>3,'user2_id'=>1]);
    }
}
