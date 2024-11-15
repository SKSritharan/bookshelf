<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //todo: register

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

    public function refresh(Request $request)
    {
        $token = auth()->user()->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'message' => 'Token refreshed',
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'user' => auth()->user(),
        ]);
    }
}
