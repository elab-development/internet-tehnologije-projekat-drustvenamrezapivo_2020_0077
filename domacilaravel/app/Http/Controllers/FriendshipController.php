<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendResource;
use App\Http\Resources\FriendShipsResource;
use App\Models\Friendship;
use App\Models\User;
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
        $friendships = Friendship::all();

        return FriendshipsResource::collection($friendships);

        // OVDE OKEJ?
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

            'user1_id' => 'required|numeric',
            'user2_id' => 'required|numeric',

        ]);
        if ($validator->fails())
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422); //OVDE


        $user1 = User::where('user_id', $request->user1_id)->first();
        $user2 = User::where('user_id', $request->user2_id)->first();

        if (!$user1 || !$user2) {
            return response()->json(['message' => 'pogresan unos'], 404); //OVDE
        }

        $friend1 = Friendship::create([

            'user1_id' => $request->user1_id,
            'user2_id' => $request->user2_id,


        ]);
        $friend2 = Friendship::create([
            'user2_id' => $request->user1_id,
            'user1_id' => $request->user2_id,


        ]);

        return response()->json(['message' => 'Friendship successfully created', 'Friend 1' => $friend1, 'Friend 2' => $friend2], 201); //OVDE
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Friendship  $friendship
     * @return \Illuminate\Http\Response
     */
    // public function friends($user1_id, $user2_id)
    // {
    //     //
    //     $friendship = Friendship::where('user1_id', $user1_id)->where('user2_id', $user2_id)->first();
    //     if (is_null($friendship)) {
    //         return response()->json(['message'=>'Data not found'], 404); //OVDE
    //     }
    //     return new FriendshipsResource($friendship);
    // }
    public function show($user1_id)
    {
        $user = User::where('user_id', $user1_id)->first();
        if (!$user) {
            return response()->json(['message' => 'ne postoji takav korisnik'], 404); //OVDE
        }
        //
        //return $user1_id;
        $friends = Friendship::where('user1_id', $user1_id)->get();


        $povratni = [];

        foreach ($friends as $friend) {

            $user = User::where('user_id', $friend->user2_id)->first();
            $povratni = [...$povratni, $user];
        }



        return response()->json(['message' => 'Friends retrieved succesfully', 'friends' => $povratni], 200); //OVDE 
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
    public function update(Request $request, $user1_id, $user2_id)
    {
        //imamo implemntaciju za izmenu svega ali nam nije logicno da postoji ikakva izmena tako da ostavljamo neimplementiranu funkciju

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Friendship  $friendship
     * @return \Illuminate\Http\Response
     */
    public function destroy($user1_id, $user2_id)
    {
        //
        $friendship = Friendship::where('user1_id', $user1_id)->where('user2_id', $user2_id)->first();
        if (!$friendship) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        // $friendship->delete();
        Friendship::where('user1_id', $user1_id)->where('user2_id', $user2_id)->delete();
        Friendship::where('user1_id', $user2_id)->where('user2_id', $user1_id)->delete();
        // $friendship = Friendship::where('user1_id', $user2_id)->where('user2_id', $user1_id);
        // $friendship->delete();

        return response()->json(['message' => 'Friendship suscesfully deleted'], 200); //OVDE
    }
}
