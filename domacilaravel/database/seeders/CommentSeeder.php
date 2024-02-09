<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $seeder = new CommentSeeder();
        $comment_id = $seeder->pomocna(1, 1);
        $seeder->pomocna(1, 2);


        Comment::create(['user_id' => 1, 'post_id' => 1, 'comment_id' => $comment_id, 'commentator_id' => 1, 'content' => 'komentar 1']);
        $comment_id = $seeder->pomocna(2, 1);

        Comment::create(['user_id' => 2, 'post_id' => 1, 'comment_id' => $comment_id, 'commentator_id' => 6, 'content' => 'komentar 2']);
        $comment_id = $seeder->pomocna(3, 1);

        Comment::create(['user_id' => 3, 'post_id' => 1, 'comment_id' => $comment_id, 'commentator_id' => 9, 'content' => 'komentar 3']);
        $comment_id = $seeder->pomocna(1, 1);

        Comment::create(['user_id' => 1, 'post_id' => 1, 'comment_id' => $comment_id, 'commentator_id' => 1, 'content' => 'komentar 4']);
        $comment_id = $seeder->pomocna(1, 2);

        Comment::create(['user_id' => 1, 'post_id' => 2, 'comment_id' => $comment_id, 'commentator_id' => 1, 'content' => 'komentar 5']);
        $comment_id = $seeder->pomocna(1, 2);

        Comment::create(['user_id' => 1, 'post_id' => 2, 'comment_id' => $comment_id, 'commentator_id' => 1, 'content' => 'komentar 6']);
    }

    public function pomocna($user_id, $post_id): int
    {

        $comments = Comment::where([
            ['user_id', '=', $user_id],
            ['post_id', '=', $post_id],
        ])->get();
        $commentId = $comments->max('comment_id') + 1;
        return $commentId;
    }
}
