<?php

namespace App\Http\Controllers\Role_User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role_User\User\UserRegisterRequest;
use App\Http\Requests\Role_User\User\UserLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['message' => 'User register successfully'], 201);
    }

    public function login(UserLoginRequest $request)
    {

        //Solo admitir email y password y excluir los demas campos de la tabla users
        $credentials = $request->only(['email', 'password']);



        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        //returns an instance of the authenticated user
        $user = $request->user();


        $token = $user->createToken('Personal access token');



        return response()->json([
            'access_token' => $token->accessToken,
            'user_id' => $user->id
        ]);
    }

    public function index() {
        return User::all();
    }

    public function user(Request $request) {
        return $request->user();
    }
}
