<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'post_id', 'reporter_id'
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id', 'user_id');
    }

    public function post()
    {

        return $this->belongsTo(Post::class, 'post_id', 'post_id')->where('user_id', '=', $this->user_id);
    }
}
