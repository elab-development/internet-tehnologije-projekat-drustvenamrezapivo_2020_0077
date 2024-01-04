<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','post_id','liker_id'
    ];
    public function liker(){
        return $this->belongsTo(User::class,'liker_id','user_id');  
    }
    public function post(){
      
     return $this->belongsTo(Post::class,'user_id','user_id')->where('post_id','=',$this->post_id);
    }
}
