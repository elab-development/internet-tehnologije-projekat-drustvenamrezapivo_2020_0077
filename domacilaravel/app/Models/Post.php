<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','post_id','content','image_path','title' 
    ];
    protected $guarded=[
        'title',
    ];
    protected $hidden=[

    ];
    public function user(){
       
       return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function likesOfPost(){
       
              return $this->hasMany(Like::class,'post_id','post_id')->where('user_id','=',$this->user_id);
            
      
       }
    
}
