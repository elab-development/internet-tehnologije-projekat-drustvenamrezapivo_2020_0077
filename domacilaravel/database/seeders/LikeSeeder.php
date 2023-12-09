<?php

namespace Database\Seeders;

use App\Models\Like;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Like::create(['user_id'=>1,'post_id'=>1,'liker_id'=>1]);
        Like::create(['user_id'=>1,'post_id'=>2,'liker_id'=>1]);
        Like::create(['user_id'=>1,'post_id'=>2,'liker_id'=>4]);
        Like::create(['user_id'=>1,'post_id'=>1,'liker_id'=>6]);
        Like::create(['user_id'=>2,'post_id'=>1,'liker_id'=>6]);
    }
}
