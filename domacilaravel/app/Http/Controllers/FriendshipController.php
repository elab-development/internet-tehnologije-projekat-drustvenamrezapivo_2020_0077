<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendResource;
use App\Http\Resources\FriendShipsResource;
use App\Models\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FriendshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $friendships=Friendship::all();

        return FriendshipsResource::collection($friendships);
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
        $validator=Validator::make($request->all(),[
           
            'user1_id'=>'required',
            'user2_id'=>'required',
            
       ]);
       if ($validator->fails())
       return response()->json($validator->errors());
      
    
       $friend1=Friendship::create([
        
           'user1_id'=>$request->user1_id,
           'user2_id'=>$request->user2_id,
           
           
       ]);
       $friend2=Friendship::create([
        'user2_id'=>$request->user1_id,
        'user1_id'=>$request->user2_id,
       
        
    ]);
      
       return response()->json(['Friendship successfully created',$friend1,$friend2]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Friendship  $friendship
     * @return \Illuminate\Http\Response
     */
    public function show($user1_id,$user2_id)
    {
        //
        $likePost=Friendship::where('user1_id',$user1_id)->where('user2_id',$user2_id)->first();
        if(is_null($likePost)){
            return response()->json('Data not found',404);
        }
        return new FriendshipsResource($likePost);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Friendship  $friendship
     * @return \Illuminate\Http\Response
     */
    public function edit(Friendship $friendship)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Friendship  $friendship
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user1_id,$user2_id)
    {
        //imamo implemntaciju za izmenu svega ali nam nije logicno da postoji ikakva izmena tako da ostavljamo neimplementiranu funkciju
        
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Friendship  $friendship
     * @return \Illuminate\Http\Response
     */
    public function destroy($user1_id,$user2_id)
    {
        //
        $likePost=Friendship::where('user1_id',$user1_id)->where('user2_id',$user2_id);
        if(is_null($likePost)){
            return response()->json('Data not found', 404);
        }
        $likePost->delete();

        $likePost=Friendship::where('user1_id',$user2_id)->where('user2_id',$user1_id);
        $likePost->delete();
        return response()->json(['message' => 'Friendship suscesfully deleted']);
    }
}
