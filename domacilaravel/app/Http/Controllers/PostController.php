<?php

namespace App\Http\Controllers;

use App\Http\Resources\ParentPostResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Contracts\Service\Attribute\Required;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return PostResource::collection($posts);
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
        $validator = Validator::make($request->all(),[

            'user_id'=>'required',
            'content'=>'required',
           // 'image_path'=>'required',
           'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'title'=>'required' 
            'location'=>'required' 
        ]);

        if ($validator->fails())
        return response()->json($validator->errors());

        $controller = new PostController();
        $post_id = $controller->pomocna($request->user_id);
        $imagePath = $request->file('image')->store('public/images');  
        $imageUrl = asset('storage/' . str_replace('public/', '', $imagePath)); 
        $post = Post::create([
            
            'user_id'=>$request->user_id,
            'post_id'=>$post_id,
            'content'=>$request->content,
           // 'image_path'=>$request->image_path,
           'image_path' => $imageUrl,
           // 'title'=>$request->title
           'location'=>$request->location
        ]);

        return response()->json(['Post successfully created',$post]);
    }

    
    public function pomocna($user_id): int
    {
        
        $posts=Post::where('user_id',$user_id)->get();
        $postId=$posts->max('post_id')+1;
        return $postId;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($user_id, $post_id)
    {
        $post = Post::where('user_id',$user_id)->where('post_id',$post_id)->first();

        if(is_null($post)){
            return response()->json('Data not found',404);
        }

        return new ParentPostResource($post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id, $post_id)
    {
        $validator = Validator::make($request->all(),[

          //  'user_id'=>'required',
            'content'=>'required',
          //  'image_path'=>'required',
           // 'title'=>'required' 
           'location'=>'required' 
        ]);

        if ($validator->fails())
        return response()->json($validator->errors());

        $post= Post::where('user_id',$user_id)->where('post_id',$post_id)->first();
          if (!$post) {
              return response()->json(['message' => 'post not found'], 404);
          }

          DB::update("
          UPDATE posts
          SET content=?, location=?
          WHERE user_id = ? AND post_id = ?
      ", [
          
          $request->content,
        //  $request->image_path,
        //  $request->title,
          $request->location,
          $user_id,
          $post_id

      ]);

      $post= Post::where('user_id',$request->user_id)->where('post_id',$request->post_id)->get();
      return response()->json(['data' => $post, 'message' => 'post sucesfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $post_id)
    {
        $post = Post::where('user_id',$user_id)->where('post_id',$post_id);

        if(is_null($post)){
            return response()->json('Data not found',404);
        }

        $post->delete();
        return response()->json(['message' => 'Post suscesfully deleted']);

    }
}
