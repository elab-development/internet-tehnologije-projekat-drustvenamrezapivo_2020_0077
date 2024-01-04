<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;


use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::All();
        

        return UserResource::collection($users);
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
            'username' => 'required|string|max:255', 
            'email' => 'required|email|string|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]); 

        if ($validator->fails())
            return response()->json($validator->errors());

       
        $user = User::create([        
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'date_of_verification'=>Carbon::now(),

        ]);

       
        $token = $user->createToken('auth_token')->plainTextToken;


       
        return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer']);  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)

    {
       

        $user = User::find($user_id);

    
        if (is_null($user)) {
            return response()->json('Data not found', 404);
        }
       
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id)
    {
        
        //return $request;
        // Validacija podataka
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => ['required','string','max:255',Rule::unique('users', 'email')->ignore($user_id, 'user_id')],
            'password' => 'required|string|min:8', // Opciono polje, ako se šalje, treba da bude string sa minimalno 8 karaktera
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Pronalaženje korisnika
        
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['message' => 'Korisnik nije pronađen'], 404);
        }

        // Ažuriranje podataka
        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Vraćanje ažuriranih podataka o korisniku
        return response()->json(['data' => $user, 'message' => 'Korisnik uspešno ažuriran']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id)
    {
        //
        $user = User::find($user_id);
        if (is_null($user)) {
            return response()->json('Data not found', 404);
        }
        $user->delete();

        return response()->json(['message' => 'User suscesfully deleted']);
    }
}
