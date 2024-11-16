<?php

namespace App\Http\Controllers\API;

use App\Helpers\NewUserRegistrationNotify;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //todo: register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $newUserRegisterNotify = new NewUserRegistrationNotify();
        $newUserRegisterNotify->sendNotification($user);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('authToken')->plainTextToken;

            return response()->json([
                'token' => $token,
                'message' => 'Login successful',
            ]);
        }

        return response()->json([
            'message' => 'Unauthorized',
        ], 401);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out',
        ]);
    }

    public function refresh()
    {
        $token = auth()->user()->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'message' => 'Token refreshed',
        ]);
    }

    public function user()
    {
        return response()->json([
            'user' => auth()->user(),
        ]);
    }
}
