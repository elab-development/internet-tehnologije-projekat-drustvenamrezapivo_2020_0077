<?php

namespace App\Http\Controllers;

use App\Http\Resources\ParentPostResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Friendship;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
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



        // $posts = Post::all();

        // foreach ($posts as $post) {
        //     
        //     //$post->image_path=$post->image_path
        //     $post->image_path = str_replace('http://localhost:8000/storage/', 'http://localhost:8000/api/', $post->image_path);
        //     $post->likes = Like::where('post_id', $post->post_id)->get();
        //     $post->comments = Comment::where('post_id', $post->post_id)->get();
        //     //$post->save();
        // }
        // return response()->json(['success' => true, 'posts' => $posts]);
    }
    public function postsOfFriends($user_id)
    {
        //return $user_id;
        $friends = Friendship::where('user1_id', $user_id)->get();
        $posts = Post::all();
        $pomocna = [];
        //rojac = 0;
        foreach ($posts as $post) {
            foreach ($friends as $friendship) {
                // if ($post->user_id == $friendship->user2_id || $post->user_id == $user_id) {
                if ($post->user_id == $friendship->user2_id) {
                    $post->image_path = str_replace('http://localhost:8000/storage/', 'http://localhost:8000/api/', $post->image_path);
                    $post->likes = Like::where('post_id', $post->post_id)->where('user_id', $post->user_id)->get();
                    foreach ($post->likes as $like) {
                        $liker = User::where('user_id', $like->liker_id)->first();
                        $like->liker = $liker;
                    }
                    $post->comments = Comment::where('post_id', $post->post_id)->where('user_id', $post->user_id)->get();
                    foreach ($post->comments as $comment) {
                        $commentator = User::where('user_id', $comment->commentator_id)->first();
                        $comment->commentator = $commentator;
                    }
                    $post->user = User::where('user_id', $post->user_id)->first();
                    $pomocna[] = $post;
                    // $brojac++;
                }
            }
        }
        //return $posts;
        return response()->json(['success' => true, 'posts' => $pomocna]);
        //   return PostResource::collection($posts);
        //   $posts = Post::all();
    }
    public function postsOfEnemies($user_id)
    {
        //return $user_id;
        $friends = Friendship::where('user1_id', $user_id)->get();
        // return $friends;
        $posts = Post::all();
        $pomocna = [];
        foreach ($posts as $post) {
            $nemaGa = true;
            foreach ($friends as $friendship) {
                // if ($post->user_id == $friendship->user2_id || $post->user_id == $user_id) {
                if ($post->user_id == $friendship->user2_id || $post->user_id == $user_id) {
                    $nemaGa = false;
                }
            }
            if ($nemaGa && $post->user_id != $user_id) {
                $post->image_path = str_replace('http://localhost:8000/storage/', 'http://localhost:8000/api/', $post->image_path);
                $post->likes = Like::where('post_id', $post->post_id)->where('user_id', $post->user_id)->get();
                foreach ($post->likes as $like) {
                    $liker = User::where('user_id', $like->liker_id)->first();
                    $like->liker = $liker;
                }
                $post->comments = Comment::where('post_id', $post->post_id)->where('user_id', $post->user_id)->get();
                foreach ($post->comments as $comment) {
                    $commentator = User::where('user_id', $comment->commentator_id)->first();
                    $comment->commentator = $commentator;
                }
                $post->user = User::where('user_id', $post->user_id)->first();
                $pomocna[] = $post;
            }
        }
        return response()->json(['success' => true, 'posts' => $pomocna]);
        //   return PostResource::collection($posts);
        //   $posts = Post::all();
    }
    public function postsOfProfile($user_id)
    {
        //return $user_id;
        // $friends = Friendship::where('user1_id', $user_id)->get();
        // return $friends;
        $posts = Post::where('user_id', $user_id)->get();

        foreach ($posts as $post) {


            $post->image_path = str_replace('http://localhost:8000/storage/', 'http://localhost:8000/api/', $post->image_path);
            $post->likes = Like::where('post_id', $post->post_id)->where('user_id', $post->user_id)->get();
            foreach ($post->likes as $like) {
                $liker = User::where('user_id', $like->liker_id)->first();
                $like->liker = $liker;
            }
            $post->comments = Comment::where('post_id', $post->post_id)->where('user_id', $post->user_id)->get();
            foreach ($post->comments as $comment) {
                $commentator = User::where('user_id', $comment->commentator_id)->first();
                $comment->commentator = $commentator;
            }
            $post->user = User::where('user_id', $post->user_id)->first();
        }
        return response()->json(['success' => true, 'posts' => $posts]);
        //   return PostResource::collection($posts);
        //   $posts = Post::all();
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
        // $imagePath = $request->file('image')->store('public/images');
        // $imageUrl = asset('api/' . str_replace('public/', '', $imagePath));
        // return $imageUrl;


        $validator = Validator::make($request->all(), [

            'user_id' => 'required',
            'content' => 'required',
            // 'image_path'=>'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'title'=>'required' 
            'location' => 'required'
        ]);
        //return $request->image;

        if ($validator->fails())
            return response()->json($validator->errors());
        $user = User::where('user_id', $request->user_id)->first();
        if (!$user) {
            return response()->json(['message' => "los unos"]);
        }
        $controller = new PostController();
        $post_id = $controller->pomocna($request->user_id);
        $imagePath = $request->file('image')->store('public/images');
        $imageUrl = asset('api/' . str_replace('public/', '', $imagePath));
        $post = Post::create([

            'user_id' => $request->user_id,
            'post_id' => $post_id,
            'content' => $request->content,
            // 'image_path'=>$request->image_path,
            'image_path' => $imageUrl,
            // 'title'=>$request->title
            'location' => $request->location
        ]);

        return response()->json(['Post successfully created', $post]);
    }


    public function pomocna($user_id): int
    {

        $posts = Post::where('user_id', $user_id)->get();
        $postId = $posts->max('post_id') + 1;
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
        $post = Post::where('user_id', $user_id)->where('post_id', $post_id)->first();

        if (is_null($post)) {
            return response()->json('Data not found', 404);
        }

        //return new ParentPostResource($post);
        // return new PostResource($post);
        return response()->json(['post' => $post]);
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
        $validator = Validator::make($request->all(), [

            //  'user_id'=>'required',
            'content' => 'required',
            //  'image_path'=>'required',
            // 'title'=>'required' 
            'location' => 'required'
        ]);


        if ($validator->fails())
            return response()->json($validator->errors());

        $post = Post::where('user_id', $user_id)->where('post_id', $post_id)->first();
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

        $post = Post::where('user_id', $request->user_id)->where('post_id', $request->post_id)->get();
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
        $post = Post::where('user_id', $user_id)->where('post_id', $post_id)->first();


        if (is_null($post)) {
            return response()->json('Data not found', 404);
        }

        $currentImagePath = $post->image_path;

        // Pretvaranje URL-a u relativnu putanju
        $relativePath = str_replace(url('/api/images'), 'public/images', $currentImagePath);

        // Stvaranje apsolutne putanje na disku
        $absolutePath = storage_path('app/' . $relativePath);

        // $post->delete();
        Post::where('user_id', $user_id)
            ->where('post_id', $post_id)
            ->delete();


        if (file_exists($absolutePath)) {
            // Slika postoji, sada je brišemo
            unlink($absolutePath);
        } else {
        }



        return response()->json(['message' => 'Post suscesfully deleted']);
    }
}
