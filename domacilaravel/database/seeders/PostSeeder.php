<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $seeder=new PostSeeder();
        
        $postId=$seeder->pomocna(1);
         Post::create(['user_id'=>1,'post_id'=>$postId,'title'=>'Post 1','content'=>'Content post 1','image_path'=>'putanja do slike1']);
         $postId=$seeder->pomocna(2);
         Post::create(['user_id'=>2,'post_id'=>$postId,'title'=>'Post 2','content'=>'Content post 2','image_path'=>'putanja do slike2']);
         $postId=$seeder->pomocna(3);
         Post::create(['user_id'=>3,'post_id'=>$postId,'title'=>'Post 3','content'=>'Content post 3','image_path'=>'putanja do slike3']);
         $postId=$seeder->pomocna(4);
         Post::create(['user_id'=>4,'post_id'=>$postId,'title'=>'Post 4','content'=>'Content post 4','image_path'=>'putanja do slike4']);
         $postId=$seeder->pomocna(5);
         Post::insert(['user_id'=>5,'post_id'=>$postId,'title'=>'Post 5','content'=>'Content post 5','image_path'=>'putanja do slike5']);
 
         $postId=$seeder->pomocna(1);
         Post::insert(['user_id'=>1,'post_id'=>$postId,'title'=>'Post 6','content'=>'Content post 6','image_path'=>'putanja do slike6']);
         $postId=$seeder->pomocna(2);
         Post::insert(['user_id'=>2,'post_id'=>$postId,'title'=>'Post 7','content'=>'Content post 7','image_path'=>'putanja do slike7']);


    }
    public function pomocna($user_id): int
    {
        
        $posts=Post::where('user_id',$user_id)->get();
        $postId=$posts->max('post_id')+1;
        return $postId;
    }
}
