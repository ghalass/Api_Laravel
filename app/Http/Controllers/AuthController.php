<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name'      => 'required|max:255',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|confirmed'
        ]);
        $user = User::create($fields);
        $token = $user->createToken($request->name);
        return [
            'user'  => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email|exists:users',
            'password'  => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'errors' => [
                    'email' => ['E-mail ou mot de passe incorrect!']
                ]
            ];
        }
        $token = $user->createToken($user->name, ['*'], now()->addMinutes(60))->plainTextToken;
        return [
            'user'  => $user,
            'token' => $token
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        // $request->user()->tokens()->delete();
        // return [
        //     'message' => "Vous êtez déconnecté!",
        // ];
    }
}
