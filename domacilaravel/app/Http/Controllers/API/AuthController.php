<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Friendship;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|email|string|max:255',
            'password' => 'required|string|min:8',
        ]);


        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json(['success' => false, 'message' => 'vec postoji taj email'], 400); //OVDE
        }

        if ($validator->fails())
            //return response()->json($validator->errors());
            return response()->json(['success' => false, 'message' => 'los unos', 'validator-errors' => $validator->errors()], 422); //OVDE


        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'picture' => "http://127.0.0.1:8000/api/images/KWpoirYG6b0No3Sha5qdLsXl4HYiiNz2z4uKtPCW.png"


        ]);


        $token = $user->createToken('auth_token')->plainTextToken;



        return response()->json(['success' => true, 'data' => $user, 'access_token' => $token, 'token_type' => 'Bearer', 'message' => 'Successful registration'], 201); //OVDE
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' => 'required|email|string|max:255',
            'password' => 'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'validator-errors' => $validator->errors(), 'message' => 'Validation failed'], 401); //OVDE
        }

        if (!Auth::attempt($request->only('email', 'password')))
            // return response()->json(['message'=>'Unautorized'],401);staro
            return response()->json(['success' => false, 'message' => 'dont exists user like this'], 401); //novo



        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        $numberOfPosts = Post::where('user_id', $user->user_id)->count();
        $user->numberOfPosts = $numberOfPosts;

        $numberOfFriends = Friendship::where('user1_id', $user->user_id)->count();
        $user->numberOfFriends = $numberOfFriends;


        $posts = Post::where('user_id', $user->user_id)->get();
        $user->posts = $posts;
        foreach ($posts as $post) {

            //$post->image_path=$post->image_path
            $post->image_path = str_replace('http://localhost:8000/storage/', 'http://localhost:8000/api/', $post->image_path);
            $post->likes = Like::where('user_id', $user->user_id)->where('post_id', $post->post_id)->get();
            $post->comments = Comment::where('user_id', $user->user_id)->where('post_id', $post->post_id)->get();
            $post->user = User::where('user_id', $post->user_id)->first();
            //$post->save();
        }

        //return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer']); staro
        return response()->json(['success' => true, 'user' => $user, 'access_token' => $token, 'token_type' => 'Bearer', 'message' => 'User successfully logged in'], 200); //novo
    }

    public function logout(Request $request)
    {
        // return $request;
        $request->user()->tokens()->delete();
        return response()->json(['success' => true, 'message' => 'user succesfully logged out']); //OVDE
    }
}
