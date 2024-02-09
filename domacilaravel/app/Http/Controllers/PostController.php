<?php

namespace App\Http\Controllers;

use App\Http\Resources\ParentPostResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\Friendship;
use App\Models\Like;
use App\Models\Post;
use App\Models\PostReport;
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
        $posts = Post::orderByDesc('created_at')->get();



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
                $reports = CommentReport::where('post_id', $comment->post_id)->where('user_id', $comment->user_id)->where('comment_id', $comment->comment_id)->get();
                $comment->reports = $reports;
            }
            $post->user = User::where('user_id', $post->user_id)->first();
            $post->reports = PostReport::where('user_id', $post->user_id)->where('post_id', $post->post_id)->get();
        }
        return response()->json(['success' => true, 'posts' => $posts, 'message' => 'Posts successfully retrieved'], 200); //OVDE





    }
    public function postsOfFriends($user_id, Request $request)
    {


        $filter = $request->input('filter');
        $setNumber = $request->input('firstN');



        $postsPerSet = 5;

        $offset = ($setNumber - 1) * $postsPerSet;

        $query = DB::table('posts')
            ->join('friendships', function ($join) use ($user_id) {
                $join->on('posts.user_id', '=', 'friendships.user2_id')
                    ->where('friendships.user1_id', '=', $user_id);
            })
            ->where('posts.user_id', '!=', $user_id)

            ->whereRaw('LOWER(posts.location) LIKE ?', ['%' . strtolower($filter) . '%'])
            ->orderBy('posts.created_at', 'desc')
            ->skip($offset)
            ->take($postsPerSet);

        $posts = $query->get();



        $pomocna = [];

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
                $reports = CommentReport::where('post_id', $comment->post_id)->where('user_id', $comment->user_id)->where('comment_id', $comment->comment_id)->get();
                $comment->reports = $reports;
                $comment->commentator = $commentator;
            }
            $post->user = User::where('user_id', $post->user_id)->first();


            $post->reports = PostReport::where('user_id', $post->user_id)->where('post_id', $post->post_id)->get();


            $pomocna[] = $post;
        }
        return response()->json(['success' => true, 'posts' => $pomocna, 'message' => 'Posts successfully retrieved'], 200); //OVDE

    }
    public function postsOfEnemies($user_id, Request $request)
    {

        $filter = $request->input('filter');

        $setNumber = $request->input('firstN');

        $postsPerSet = 5;

        $offset = ($setNumber - 1) * $postsPerSet;

        $query = DB::table('posts')
            ->leftJoin('friendships', function ($join) use ($user_id) {
                $join->on('posts.user_id', '=', 'friendships.user2_id')
                    ->where('friendships.user1_id', '=', $user_id);
            })
            ->whereNull('friendships.user1_id')
            ->where('posts.user_id', '!=', $user_id)

            ->whereRaw('LOWER(posts.location) LIKE ?', ['%' . strtolower($filter) . '%'])
            ->orderBy('posts.created_at', 'desc')
            ->skip($offset)
            ->take($postsPerSet);

        $posts = $query->get();


        $pomocna = [];
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
                $reports = CommentReport::where('post_id', $comment->post_id)->where('user_id', $comment->user_id)->where('comment_id', $comment->comment_id)->get();
                $comment->reports = $reports;
            }
            $post->user = User::where('user_id', $post->user_id)->first();
            $post->reports = PostReport::where('user_id', $post->user_id)->where('post_id', $post->post_id)->get();
            $pomocna[] = $post;
        }
        return response()->json(['success' => true, 'posts' => $pomocna, 'message' => 'Posts successfully retrieved'], 200); //OVDE

    }
    public function postsOfProfile($user_id, Request $request)
    {

        $filter = $request->input('filter');
        $firstN = $request->input('firstN');
        $pomocna = $firstN * 5 - 5;

        $posts = Post::whereRaw('LOWER(location) LIKE ?', ['%' . strtolower($filter) . '%'])->where('user_id', $user_id)
            ->orderBy('created_at', 'desc')->skip($pomocna)->take(5)
            ->get();

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
                $reports = CommentReport::where('post_id', $comment->post_id)->where('user_id', $comment->user_id)->where('comment_id', $comment->comment_id)->get();
                $comment->reports = $reports;
            }
            $post->user = User::where('user_id', $post->user_id)->first();
            $post->reports = PostReport::where('user_id', $post->user_id)->where('post_id', $post->post_id)->get();
        }
        return response()->json(['success' => true, 'posts' => $posts, 'message' => 'Posts successfully retrieved'], 200); //OVDE

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
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422); //OVDE

        $user = User::where('user_id', $request->user_id)->first();
        if (!$user) {
            return response()->json(['message' => "los unos"], 404); //OVDE
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

        return response()->json(['message' => 'Post successfully created', 'Post' => $post], 201); //OVDE
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
            return response()->json(['message' => 'Data not found'], 404); //OVDE
        }

        //return new ParentPostResource($post);
        // return new PostResource($post);
        return response()->json(['message' => 'Post successfully retrieved', 'Post' => $post], 200); //OVDE
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
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422); //OVDE

        $post = Post::where('user_id', $user_id)->where('post_id', $post_id)->first();
        if (!$post) {
            return response()->json(['message' => 'post not found'], 404); //OKEJ
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
        return response()->json(['data' => $post, 'message' => 'post sucesfully updated'], 201); //OVDE
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
            return response()->json(['message' => 'Data not found'], 404); // OVDE
        }

        $currentImagePath = $post->image_path;

        // Pretvaranje URL-a u relativnu putanju
        $relativePath = str_replace(url('/api/images'), 'public/images', $currentImagePath);

        // Stvaranje apsolutne putanje na disku
        $absolutePath = storage_path('app/' . $relativePath);

        if (file_exists($absolutePath) && $currentImagePath != "http://127.0.0.1:8000/api/images/KWpoirYG6b0No3Sha5qdLsXl4HYiiNz2z4uKtPCW.png") {
            // Slika postoji, sada je brišemo
            unlink($absolutePath);
        } else {
        }

        // $post->delete();
        Post::where('user_id', $user_id)
            ->where('post_id', $post_id)
            ->delete();




        return response()->json(['message' => 'Post successfully deleted'], 200); //OVDE
    }



    public function offensive()
    {
        // $posts = Post::where('numberOfReports', '>', 0)->get();
        // $comments = Comment::where('numberOfReports', '>', 0)->get();
        // return response()->json(['success' => true, 'posts' => $posts, 'comments' => $comments], 200);

        $groupedReports1 = DB::table('post_reports')
            ->select('user_id', 'post_id', DB::raw('COUNT(*) as numberOfReports'))
            ->groupBy('user_id', 'post_id')
            ->get();

        $groupedReports2 = DB::table('comment_reports')
            ->select('user_id', 'post_id', 'comment_id', DB::raw('COUNT(*) as numberOfReports'))
            ->groupBy('user_id', 'post_id', 'comment_id')
            ->get();



        $postsWithReports = [];

        foreach ($groupedReports1 as $report) {
            $post = Post::where('user_id', $report->user_id)
                ->where('post_id', $report->post_id)
                ->first();

            if ($post) {
                $post->numberOfReports = $report->numberOfReports;
                $postsWithReports[] = $post;
            }
        }
        $commentsWithReports = [];

        foreach ($groupedReports2 as $report) {
            $comment = Comment::where('user_id', $report->user_id)->where('comment_id', $report->comment_id)
                ->where('post_id', $report->post_id)
                ->first();

            if ($comment) {
                $comment->numberOfReports = $report->numberOfReports;
                $commentsWithReports[] = $comment;
            }
        }



        return response()->json(['posts' => $postsWithReports, 'comments' => $commentsWithReports], 200);
    }


    public function addPostReport(Request $request)
    {
        //


        $validator = Validator::make($request->all(), [

            'user_id' => 'required',
            'post_id' => 'required',
            'reporter_id' => 'required',

        ]);
        if ($validator->fails())
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422); //OVDE

        $post = Post::where('user_id', $request->user_id)->where('post_id', $request->post_id)->first();
        $user = User::where('user_id', $request->reporter_id)->first();
        if (!$post || !$user) {
            return response()->json(["message" => 'los unos'], 404); //OVDE
        }
        $postReport = PostReport::create([


            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'reporter_id' => $request->reporter_id,
        ]);

        return response()->json(['message' => 'Report of post successfully created', 'PostReport' => $postReport], 201); //OVDE
    }
    public function addCommentReport(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [

            'user_id' => 'required',
            'post_id' => 'required',
            'comment_id' => 'required',
            'reporter_id' => 'required',

        ]);
        if ($validator->fails())
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422); ////OVDE

        $comment = Comment::where('user_id', $request->user_id)->where('post_id', $request->post_id)->where('comment_id', $request->comment_id)->first();
        $user = User::where('user_id', $request->reporter_id)->first();

        if (!$comment || !$user) {
            return response()->json(['message' => 'wrong parameters'], 404); //OVDE STAVIO
        }


        $commentReport = CommentReport::create([


            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'comment_id' => $request->comment_id,

            'reporter_id' => $request->reporter_id,

        ]);
        //vracamo podatke
        return response()->json(['Report of comment successfully created', $commentReport], 201); //OVDE STAVIO
    }

    public function removePostReport($user_id, $post_id, $reporter_id)
    {
        //
        $postReport = PostReport::where('user_id', $user_id)->where('post_id', $post_id)->where('reporter_id', $reporter_id)->first();
        if (!$postReport) {
            return response()->json(['message' => 'Data not found'], 404); // OVDE
        }
        PostReport::where('user_id', $user_id)->where('post_id', $post_id)->where('reporter_id', $reporter_id)->delete();
        return response()->json(['message' => 'Post report suscesfully deleted'], 200); //OVDE
    }
    public function removeCommentReport($user_id, $post_id, $comment_id, $reporter_id)
    {
        //
        $commentReport = CommentReport::where('user_id', $user_id)->where('post_id', $post_id)->where('reporter_id', $reporter_id)->where('comment_id', $comment_id)->first();
        if (!$commentReport) {
            return response()->json(['message' => 'Data not found'], 404); // OVDE
        }
        CommentReport::where('user_id', $user_id)->where('post_id', $post_id)->where('reporter_id', $reporter_id)->where('comment_id', $comment_id)->delete();
        return response()->json(['message' => 'Comment report suscesfully deleted'], 200); //OVDE
    }
}
