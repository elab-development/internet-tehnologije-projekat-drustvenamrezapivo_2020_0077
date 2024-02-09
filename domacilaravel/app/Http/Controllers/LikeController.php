<?php

namespace App\Http\Controllers;

use App\Http\Resources\LikeResource;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $likes = Like::all();
        return LikeResource::collection($likes);

        //OK ZBOG RESURSA
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //


        $validator = Validator::make($request->all(), [

            'user_id' => 'required',
            'post_id' => 'required',
            'liker_id' => 'required',

        ]);
        if ($validator->fails())
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422); //OVDE

        $post = Post::where('user_id', $request->user_id)->where('post_id', $request->post_id)->first();
        $user = User::where('user_id', $request->liker_id)->first();
        if (!$post || !$user) {
            return response()->json(["message" => 'los unos'], 404); //OVDE
        }
        $post = Like::create([


            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'liker_id' => $request->liker_id,
        ]);

        return response()->json(['message' => 'Like of post successfully created', 'Post' => $post], 201); //OVDE
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function show($user_id, $post_id, $liker_id)
    {
        //
        $likePost = Like::where('user_id', $user_id)->where('post_id', $post_id)->where('liker_id', $liker_id)->first();
        if (is_null($likePost)) {
            return response()->json(['message' => 'Data not found'], 404); //OVDE
        }
        return new LikeResource($likePost);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function edit(Like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id, $post_id, $liker_id)
    {
        // Imamo implementaciju u slucaju da zelimo sve da izmenimo, ali nam to deluje nelogicno
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $post_id, $liker_id)
    {
        //
        $likePost = Like::where('user_id', $user_id)->where('post_id', $post_id)->where('liker_id', $liker_id)->first();
        if (!$likePost) {
            return response()->json(['message' => 'Data not found'], 404); //OVDE
        }
        // return $likePost;
        // $likePost->delete();
        Like::where('user_id', $user_id)->where('post_id', $post_id)->where('liker_id', $liker_id)->delete();
        return response()->json(['message' => 'Like suscesfully deleted', 'likepost' => $likePost], 200); //OVDE
    }
}
