<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
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
Route::delete('/comments/{user_id}/{post_id}/{comment_id}',[CommentController::class,'destroy']);
Route::post('/comments',[CommentController::class,'store']);
Route::put('/comments/{user_id}/{post_id}/{comment_id}',[CommentController::class,'update']);


Route::resource('/likes',LikeController::class);
Route::get('/likes/{user_id}/{post_id}/{liker_id}',[LikeController::class,'show']);
Route::delete('/likes/{user_id}/{post_id}/{liker_id}',[LikeController::class,'destroy']);
Route::post('/likes',[LikeController::class,'store']);
//Route::put('/likes/{user_id}/{post_id}/{liker_id}',[LikeController::class,'update']);


