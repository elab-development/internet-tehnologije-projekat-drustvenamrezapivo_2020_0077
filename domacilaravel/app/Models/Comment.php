<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content','user_id','post_id','commentator_id','comment_id'
    ];

    public function commentator(){
        return $this->belongsTo(User::class,'commentator_id','user_id');  
    }
    public function post(){
       
         return $this->belongsTo(Post::class,'post_id','post_id')->where('user_id','=',$this->user_id);
    }
    
   
}
