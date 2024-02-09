<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Comment;
use App\Models\Friendship;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use Faker\Core\File;
use Faker\Provider\File as ProviderFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Mockery\Undefined;
use PhpParser\Node\Expr\Cast\String_;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use function Symfony\Component\String\b;

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


        return  $users = UserResource::collection($users);
        //return response()->json(['success' => true, 'users' => $users]);
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
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422); //OVDE


        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_of_verification' => Carbon::now(),



        ]);


        $token = $user->createToken('auth_token')->plainTextToken;


        $user = User::where('email', $request->email)->first();

        return response()->json(['message' => 'User successfully created', 'data' => $user, 'access_token' => $token, 'token_type' => 'Bearer'], 201); //OVDE
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
            return response()->json(['message' => 'Data not found'], 404); //OVDE
        }

        // return response()->json($user);




        // $token = $user->createToken('auth_token')->plainTextToken;

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
            $post->user = User::where('user_id', $post->user_id)->get();
            //$post->save();
        }

        //return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer']); staro
        return response()->json(['success' => true, 'user' => $user, 'message' => 'User successfully retrieved'], 200); //novo
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
    // public function setPicture(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'user_id' => 'required|string|max:255',
    //         'username' => 'required|string|max:255',
    //         'email' => ['required', 'string', 'max:255', Rule::unique('users', 'email')->ignore($request->user_id, 'user_id')],
    //         // 'password' => 'required|string|min:8',
    //         'picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
    //     ]);
    //     if ($validator->fails()) {
    //         //return "ovde";
    //         return response()->json(["message1" => $validator->errors()], 400);
    //     }

    //     $user = User::find($request->user_id);

    //     if (!$user) {
    //         return response()->json(['message' => 'Korisnik nije pronađen'], 404);
    //     }

    //     if ($request->picture) {

    //         $imagePath = $request->file('picture')->store('public/images');
    //         $imageUrl = asset('storage/' . str_replace('public/', '', $imagePath));
    //         //return $imageUrl;

    //         // return $imageUrl;
    //         // $user->update([
    //         //     'username' => $request->username,
    //         //     'email' => $request->email,
    //         //     'password' => Hash::make($request->password),
    //         //     'picture' => $imageUrl,
    //         // ]);




    //         if ($request->password instanceof String) {
    //             return $request->password;
    //             return "upisao sifru";

    //             DB::update("
    //         UPDATE users
    //         SET name=?, email=? password=?,picture=?,about=?
    //         WHERE user_id =?
    //     ", [

    //                 $request->username,

    //                 //  $request->image_path,
    //                 //  $request->title,
    //                 $request->email,
    //                 Hash::make($request->password),
    //                 $imageUrl,
    //                 $request->about,
    //                 $request->user_id


    //             ]);
    //         } else {
    //             return "Nije upisao sifru znaci postavi mu staru";

    //             DB::update("
    //             UPDATE users
    //             SET name=?, email=?,picture=?,about=?
    //             WHERE user_id =?
    //         ", [

    //                 $request->username,

    //                 //  $request->image_path,
    //                 //  $request->title,
    //                 $request->email,

    //                 $imageUrl,
    //                 $request->about,
    //                 $request->user_id


    //             ]);
    //         }



    //         //     DB::update("
    //         //     UPDATE users
    //         //     SET name=?, email=? password=?,picture=?,about=?
    //         //     WHERE user_id =?
    //         // ", [

    //         //         $request->username,

    //         //         //  $request->image_path,
    //         //         //  $request->title,
    //         //         $request->email,
    //         //         Hash::make($request->password),
    //         //         $imageUrl,
    //         //         $request->about,
    //         //         $request->user_id


    //         //     ]);
    //     } else {
    //         if ($request->password instanceof String) {
    //             $user->update([
    //                 'username' => $request->username,
    //                 'email' => $request->email,
    //                 'password' => Hash::make($request->password),

    //             ]);
    //         } else {
    //             $user->update([
    //                 'username' => $request->username,
    //                 'email' => $request->email,


    //             ]);
    //         }
    //     }

    //     // Ažuriranje podataka

    //     $user = User::find($request->user_id);
    //     // Vraćanje ažuriranih podataka o korisniku
    //     return response()->json(['data' => $user, 'message' => 'Korisnik uspešno ažuriran']);
    // }
    public function update(Request $request, $user_id)
    {

        // return $request;
        // Validacija podataka
        $validator = Validator::make($request->all(), [
            //'username' => 'required|string|max:255',
            //'email' => ['required', 'string', 'max:255', Rule::unique('users', 'email')->ignore($user_id, 'user_id')],
            //'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422); //OVDE
        }
        if (isset($request->picture)) {
            // return $request->picture;
            $validator = Validator::make($request->all(), [
                'picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048'

            ]);
            if ($validator->fails()) {
                return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422); //OVDE
            }
        }

        // Pronalaženje korisnika
        $password = $request->password;

        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['message' => 'Korisnik nije pronađen'], 404); //OKEJ
        }
        // return $request->about;
        //return $request->picture;

        if (isset($request->picture)) {
            //  return "poslao sliku";
            //return $request->file('picture');
            $imagePath = $request->file('picture')->store('public/images');
            // $imageUrl = asset('storage/' . str_replace('public/', '', $imagePath)); bilo ovako
            $imageUrl = asset('api/' . str_replace('public/', '', $imagePath));
            //return $imageUrl;
            $user->update([
                //'username' => $request->username,
                'email' => $request->email,
                // 'password' => Hash::make($request->password),
                'picture' => $imageUrl,
                'about' => $request->about,
                'name' => $request->username
            ]);
            if ($password != "undefined") {
                //return "uneo novu sifru";
                //return $request;
                // return $request->about;
                //  return "poslao novu sifru";
                //return $request->password;
                $user->update([
                    //'username' => $request->username,
                    // 'email' => $request->email,
                    'password' => Hash::make($request->password),
                    //'about' => $request->about,
                    // 'name' => $request->username,

                ]);
            }
            // $user = User::find($user_id);
            // return $user;
        } else {
            //return "nije poslao sliku";
            //return $request;
            $user->update([
                //'username' => $request->username,
                'email' => $request->email,
                //'password' => Hash::make($request->password),
                'about' => $request->about,
                'name' => $request->username,

            ]);
            if ($password != "undefined") {
                // return "uneo novu sifru";
                //return $request->password;
                // return "poslao novu sifru";
                $user->update([
                    //'username' => $request->username,
                    // 'email' => $request->email,
                    'password' => Hash::make($request->password),
                    //'about' => $request->about,
                    // 'name' => $request->username,

                ]);
            }
            // $user = User::find($user_id);
            // return $user;
        }

        // Ažuriranje podataka


        // Vraćanje ažuriranih podataka o korisniku
        return response()->json(['data' => $user, 'message' => 'Korisnik uspešno ažuriran'], 201); //OVDE
    }
    public function updateWithPicture(Request $request)
    {
        //post
        // return $request;




        // return $request;
        // Validacija podataka
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            //'email' => ['required', 'string', 'max:255', Rule::unique('users', 'email')->ignore($user_id, 'user_id')],
            //'password' => 'required|string|min:8',
            'email' => 'required|string|max:255',
            'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422); //OVDE
        }

        // Pronalaženje korisnika
        $password = $request->password;

        $user = User::find($request->user_id);


        if (!$user) {
            return response()->json(['message' => 'Korisnik nije pronađen'], 404); //OKEJ
        }

        // $path = $user->picture;

        // // Pronalaženje pozicije 'images' i dobijanje dela putanje posle toga
        // $finalPath = substr($path, strpos($path, 'images'));

        $currentImagePath = $user->picture;

        // return $request->file('picture');

        // Pretvaranje URL-a u relativnu putanju
        $relativePath = str_replace(url('/api/images'), 'public/images', $currentImagePath);

        // Stvaranje apsolutne putanje na disku
        $absolutePath = storage_path('app/' . $relativePath);





        //  return "poslao sliku";
        //return $request->file('picture');

        $imagePath = $request->file('picture')->store('public/images');
        //api/images/SgxrY1VnbOrcdFxNFEf28m7Msmg186ymRA2vRBLk.jpg
        // $imageUrl = asset('storage/' . str_replace('public/', '', $imagePath)); bilo ovako
        $imageUrl = asset('api/' . str_replace('public/', '', $imagePath));

        if ($user->email == $request->email) {
            //return "nije menjao email";
            $user->update([
                //'username' => $request->username,
                //'email' => $request->email,
                // 'password' => Hash::make($request->password),
                'picture' => $imageUrl,
                'about' => $request->about,
                'name' => $request->username
            ]);
            // Provera da li slika postoji pre nego što je obrišemo
            if (file_exists($absolutePath) && $currentImagePath != "http://127.0.0.1:8000/api/images/KWpoirYG6b0No3Sha5qdLsXl4HYiiNz2z4uKtPCW.png") {
                // Slika postoji, sada je brišemo
                unlink($absolutePath);
            } else {
            }
        } else {
            $otherUserExists = User::where('email', $request->email)->exists();
            if ($otherUserExists) {
                return response()->json(['message' => 'vec postoji korisnik sa ovim emailom'], 400); //OVDE
            }

            $user->update([
                //'username' => $request->username,
                'email' => $request->email,
                // 'password' => Hash::make($request->password),
                'picture' => $imageUrl,
                'about' => $request->about,
                'name' => $request->username
            ]);
            // Provera da li slika postoji pre nego što je obrišete
            if (file_exists($absolutePath) && $currentImagePath != "http://127.0.0.1:8000/api/images/KWpoirYG6b0No3Sha5qdLsXl4HYiiNz2z4uKtPCW.png") {
                // Slika postoji, sada je brišemo
                unlink($absolutePath);
            } else {
            }




            //return "menjao mejl";
        }
        //return $imageUrl;



        if ($password != "undefined") {
            //return "uneo novu sifru";
            //return $request;
            // return $request->about;
            //  return "poslao novu sifru";
            //return $request->password;
            $user->update([
                //'username' => $request->username,
                // 'email' => $request->email,
                'password' => Hash::make($request->password),
                //'about' => $request->about,
                // 'name' => $request->username,

            ]);
        }
        // $user = User::find($user_id);
        // return $user;

        // $user = User::find($user_id);
        // return $user;

        return response()->json(['data' => $user, 'message' => 'Korisnik uspešno ažuriran'], 201); //OVDE

        // Ažuriranje podataka
    }
    public function updateWithoutPicture(Request $request, $user_id)
    {

        //return $user_id;
        //put 

        // return $request;
        // Validacija podataka
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            // 'email' => ['required', 'string', 'max:255', Rule::unique('users', 'email')->ignore($user_id, 'user_id')],
            // 'password' => 'required|string|min:8',
            'email' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422); //OVDE
        }


        // Pronalaženje korisnika
        $password = $request->password;

        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['message' => 'Korisnik nije pronađen'], 404); //OKEJ
        }
        // return $request->about;
        //return $request->picture;


        //return "nije poslao sliku";
        //return $request;



        if ($user->email == $request->email) {
            //return 1;
            //return "nije menjao email";
            // return $request->email;
            $user->update([
                //'username' => $request->username,
                //'email' => $request->email,
                // 'password' => Hash::make($request->password),

                'about' => $request->about,
                'name' => $request->username
            ]);
        } else {
            $otherUserExists = User::where('email', $request->email)->exists();
            if ($otherUserExists) {
                return response()->json(['message' => 'vec postoji korisnik sa ovim emailom'], 400); //OKEJ
            }
            //return $request->about;

            $user->update([
                //'username' => $request->username,
                'email' => $request->email,
                // 'password' => Hash::make($request->password),

                'about' => $request->about,
                'name' => $request->username
            ]);

            //return "menjao mejl";
        }













        if ($password != "undefined") {
            // return "uneo novu sifru";
            //return $request->password;
            // return "poslao novu sifru";
            $user->update([
                //'username' => $request->username,
                // 'email' => $request->email,
                'password' => Hash::make($request->password),
                //'about' => $request->about,
                // 'name' => $request->username,

            ]);
        }


        // $user = User::find($user_id);
        // return $user;


        // Ažuriranje podataka


        // Vraćanje ažuriranih podataka o korisniku
        return response()->json(['data' => $user, 'message' => 'Korisnik uspešno ažuriran'], 201); //OVDE
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    // public function savePicture($request)

    // {

    //     $validator = Validator::make($request->all(), [
    //         'picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    //         //  'user_id' => 'required|string|max:255',

    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 400);
    //     }

    //     $imagePath = $request->file('image')->store('public/images');
    //     $imageUrl = asset('storage/' . str_replace('public/', '', $imagePath));
    //     $user = User::find($request->user_id);
    //     if (!$user) {
    //         return response()->json(['message' => 'Korisnik nije pronađen'], 404);
    //     }

    //     // return $imageUrl;
    //     $user->update([
    //         //'username' => $request->username,
    //         // 'email' => $request->email,
    //         // 'password' => Hash::make($request->password),
    //         'picture' => $imageUrl,
    //         // 'about' => $request->about,
    //         //name' => $request->username
    //     ]);
    //     return response()->json(['data' => $user, 'message' => 'Korisnik uspešno ažuriran']);
    // }
    public function destroy($user_id)
    {
        //
        $user = User::find($user_id);
        if (is_null($user)) {
            return response()->json(['message' => 'Data not found'], 404); //OVDE
        }
        $user->delete();

        return response()->json(['message' => 'User suscesfully deleted'], 200); //OVDE
    }


    public function getImage($imageName)
    {
        //  return $imageName;
        $imagePath = storage_path("app/public/images/{$imageName}");
        // return $imagePath;
        if (file_exists($imagePath)) {
            // return response()->file($imagePath);

            // return response()->json(['image_url' => asset("storage/images/{$imageName}")])->header('Content-Type', 'application/json; charset=utf-8');
            return response()->file($imagePath);
        } else {
            return response()->json(['error' => 'Slika nije pronađena'], 404); //OKEJ
        }
    }
    // public function numberOfPosts($user_id)
    // {

    //     $x = Post::where('user_id', $user_id)->count();
    //     return $x;
    // }
    // public function numberOfFriends($user_id)
    // {

    //     $x = Friendship::where('user1_id', $user_id)->count();
    //     return $x;
    // }
  /*  public function resetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'email' => 'required|email|string|max:255',
            'password' => 'required|string|min:8',

        ]);

        if ($validator->fails())
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422); //OVDE



        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Korisnik nije pronađen'], 404);
        }
        DB::update("
                 UPDATE users
               SET password=?
              WHERE email =?
             ", [
            Hash::make($request->password),
            $request->email,
        ]);

        return response()->json(['data' => $user, 'message' => 'Sifra  uspešno ažurirana'], 201);
    }*/
    public function info()
    {
        $columnCountUsers = DB::select("SELECT COUNT(*) as count FROM users");
        $columnCountPosts = DB::select("SELECT COUNT(*) as count FROM posts");
        $columnCountAdmins = DB::select("SELECT COUNT(*) as count FROM users where role='admin'");


        $numberOfPosts = $columnCountPosts[0]->count;
        $numberOfUsers = $columnCountUsers[0]->count;
        $numberOfAdmins = $columnCountAdmins[0]->count;



        return response()->json(['numberOfPosts' => $numberOfPosts, 'numberOfUsers' => $numberOfUsers, 'numberOfAdmins' => $numberOfAdmins], 200);
    }
    public function mostActive()
    {

        $maxPosts = DB::table('posts')
            ->selectRaw('COUNT(*) as max_posts')
            ->whereYear('created_at', '=', now()->year)
            ->whereMonth('created_at', '=', now()->month)
            ->groupBy('user_id')
            ->orderByDesc('max_posts')
            ->limit(1)
            ->value('max_posts');


        $usersWithMaxPosts = User::select('users.*')
            ->join('posts', 'users.user_id', '=', 'posts.user_id')
            ->whereYear('posts.created_at', '=', now()->year)
            ->whereMonth('posts.created_at', '=', now()->month)
            ->groupBy('users.user_id', 'users.name', 'users.email', 'users.date_of_verification', 'users.password', 'users.remember_token', 'users.created_at', 'users.updated_at', 'users.picture', 'users.about', 'users.role')
            ->havingRaw('COUNT(*) = ?', [$maxPosts])
            ->get();

        return response()->json(['users' => $usersWithMaxPosts], 200);
    }
    public function setAdmin(Request $request, $user_id)
    {
        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['message' => 'Korisnik nije pronađen'], 404); //OKEJ
        }
        $user->update([
            //'username' => $request->username,
            // 'email' => $request->email,
            // 'password' => Hash::make($request->password),
            //'about' => $request->about,
            // 'name' => $request->username,
            'role' => "admin"

        ]);

        DB::update("
         UPDATE users
          SET role=?
           WHERE user_id =?
       ", [

            "admin",
            $user_id


        ]);




        $user = User::find($user_id);
        return response()->json(['message' => 'Korisnik uspešno ažuriran', 'user' => $user], 201); //OVDE


    }

    public function sendResetLinkEmailApi(Request $request) {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(60);
        Log::info('Generated Token: ' . $token);
        $passwordReset = DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        if ($passwordReset) {
            // Send Email
            Mail::to($request->email)->send(new PasswordResetMail($token));

            return response()->json([
                'message' => 'We have e-mailed your password reset link!'
            ]);
        }

        return response()->json(['message' => 'Error during password reset'], 500);
    }

    public function showResetForm(Request $request, $token = null)
{
   //   return view('emails.reset', ['token' => $token]);

   return view('emails.reset', ['token' => $token]);

}


    public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed',
    ]);

    // Retrieve all tokens for the given email
    $passwordResets = DB::table('password_resets')->where('email', $request->email)->get();

    $tokenValid = false;
    foreach ($passwordResets as $passwordReset) {
        if (Hash::check($request->token, $passwordReset->token)) {
            $tokenValid = true;
            break;
        }
    }

    if (!$tokenValid) {
        return response()->json(['message' => 'Invalid token'], 400);
    }

    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json(['message' => 'User does not exist'], 404);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    // Delete password reset tokens for this user
    DB::table('password_resets')->where(['email'=> $request->email])->delete();

    return response()->json(['message' => 'Password successfully reset']);
}
}
