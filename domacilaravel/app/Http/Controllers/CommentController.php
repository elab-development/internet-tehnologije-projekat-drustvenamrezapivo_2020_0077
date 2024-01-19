<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $comments = Comment::all();
        //return $comments;
        return CommentResource::collection($comments);

        //OVDE OK?

        
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
            'commentator_id' => 'required',
            'content' => 'required|string|max:255'

        ]);
        if ($validator->fails())
            return response()->json(['message' => 'Validation failed', 'errors'=>$validator->errors()],422); ////OVDE

        $post = Post::where('user_id', $request->user_id)->where('post_id', $request->post_id)->first();
        $user = User::where('user_id', $request->commentator_id)->first();

        if (!$post || !$user) {
            return response()->json(['message' => 'wrong parameters'],404); //OVDE STAVIO
        }

        $controller = new CommentController();
        $comment_id = $controller->pomocna($request->user_id, $request->post_id);
        $comment = Comment::create([


            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
            'comment_id' => $comment_id,

            'commentator_id' => $request->commentator_id,
            'content' => $request->content,
        ]);
        //vracamo podatke
        return response()->json(['comment successfully created', $comment],201); //OVDE STAVIO
    }
    function pomocna($user_id, $post_id): int
    {
        $comments = Comment::where([
            ['user_id', '=', $user_id],
            ['post_id', '=', $post_id],
        ])->get();
        $commentId = $comments->max('comment_id') + 1;
        return $commentId;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show($user_id, $post_id, $comment_id)
    {
        //
        $comment = Comment::where('user_id', $user_id)->where('post_id', $post_id)->where('comment_id', $comment_id)->first();
        if (is_null($comment)) {
            return response()->json(['message'=> 'Data not found'], 404); // OVDE STAVIO
        }
        return response()->json(['comment' => $comment, 'success' => true, 'message' => 'Comment successfully retrieved'],200); // OVDE STAVIO
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id, $post_id, $comment_id)
    {
        //
        $validator = Validator::make($request->all(), [

            //'user_id' => 'required',
            //'post_id' => 'required', 
            //'comment_id'=>'required',
            //'commentator_id'=>'required',
            'content' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors'=>$validator->errors()],422);
        }





        $comment = Comment::where('user_id', $user_id)->where('post_id', $post_id)->where('comment_id', $comment_id)->first();
        if (!$comment) {
            return response()->json(['message' => 'comment not found'], 404); // OK
        }


        DB::update("
          UPDATE comments
          SET content=?
          WHERE user_id = ? AND post_id = ? AND comment_id=?
      ", [

            $request->content,
            $user_id,
            $post_id,
            $comment_id,

        ]);

        $comment = Comment::where('user_id', $request->user_id)->where('post_id', $request->post_id)->where('comment_id', $request->comment_id)->get();
        return response()->json(['data' => $comment, 'message' => 'comment sucesfully updated'],201); //OVDE
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id, $post_id, $comment_id)
    {
        //
        $comment = Comment::where('user_id', $user_id)->where('post_id', $post_id)->where('comment_id', $comment_id)->first();
        if (!$comment) {
            return response()->json(['message' => 'Data not found'], 404); // OVDE
        }
        Comment::where('user_id', $user_id)->where('post_id', $post_id)->where('comment_id', $comment_id)->delete();
        return response()->json(['message' => 'comment suscesfully deleted'], 200); //OVDE
    }
}
