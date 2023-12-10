<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::resource('/comments',CommentController::class);
Route::get('/comments/{user_id}/{post_id}/{comment_id}',[CommentController::class,'show']);
//Route::delete('/comments/{user_id}/{post_id}/{comment_id}',[CommentController::class,'destroy']);
//Route::post('/comments',[CommentController::class,'store']);
//Route::put('/comments/{user_id}/{post_id}/{comment_id}',[CommentController::class,'update']);


Route::resource('/likes',LikeController::class);
Route::get('/likes/{user_id}/{post_id}/{liker_id}',[LikeController::class,'show']);
//Route::delete('/likes/{user_id}/{post_id}/{liker_id}',[LikeController::class,'destroy']);
//Route::post('/likes',[LikeController::class,'store']);
//Route::put('/likes/{user_id}/{post_id}/{liker_id}',[LikeController::class,'update']);


Route::resource('/friendships',FriendshipController::class);
Route::get('/friendships/{user1_id}/{user2_id}',[FriendshipController::class,'show']);
//Route::delete('/friendships/{user1_id}/{user2_id}',[FriendshipController::class,'destroy']);
//Route::post('/friendships',[FriendshipController::class,'store']);
//Route::put('/friendships/{user1_id}/{user2_id}',[FriendshipController::class,'update']);


Route::resource('/posts',PostController::class);
Route::get('/posts/{user_id}/{post_id}',[PostController::class,'show']);
//Route::delete('/posts/{user_id}/{post_id}',[PostController::class,'destroy']);
//Route::post('/posts',[PostController::class,'store']);
//Route::put('posts/{user_id}/{post_id}',[PostController::class,'update']);


Route::resource('/users',UserController::class);  //radi 
Route::get('/users/{user_id}',[UserController::class,'show']);//radi 
Route::post('/users', [UserController::class, 'store']);
//Route::put('/users/{user_id}', [UserController::class, 'update']);
//Route::delete('/users/{user_id}', [UserController::class, 'destroy']);//radi 


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);


Route::group(['middleware'=>['auth:sanctum']],function(){  
    Route::get('/profile',function(Request $request){
        return auth()->user();
    });
    
    Route::resource('users',UserController::class)->only(['update','store','destroy']);
    Route::resource('posts',PostController::class)->only(['update','store','destroy']);
    Route::resource('likes',LikeController::class)->only(['update','store','destroy']);
    Route::resource('comments',CommentController::class)->only(['update','store','destroy']);
    Route::resource('friendships',FriendshipController::class)->only(['update','store','destroy']);
    Route::post('/logout', [AuthController::class,'logout']);
  });