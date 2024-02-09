<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'post_id', 'comment_id', 'reporter_id'
    ];



    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id', 'user_id');
    }

    public function comment()
    {

        return $this->belongsTo(Comment::class, 'post_id', 'post_id')->where('user_id', '=', $this->user_id)->where('comment_id', '=', $this->comment_id);
    }
}
