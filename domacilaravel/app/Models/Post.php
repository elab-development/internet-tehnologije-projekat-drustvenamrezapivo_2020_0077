<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','post_id','content','image_path','location' 
    ];
  
    protected $hidden=[

    ];
    public function user(){
       
       return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function likesOfPost(){
       
              return $this->hasMany(Like::class,'post_id','post_id')->where('user_id','=',$this->user_id);
            
      
   }
   public function commentsOfPost(){
    return $this->hasMany(Comment::class,'user_id','user_id')->where('post_id','=',$this->post_id);
   }
    
}
