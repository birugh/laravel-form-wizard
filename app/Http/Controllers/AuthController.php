<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiTokenResource;
use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Account is inactive'
            ], 403);
        }

        $tokenString = Str::random(60);

        $apiToken = ApiToken::create([
            'user_id' => $user->id,
            'token' => hash('sha256', $tokenString),
            'expires_at' => now()->addDay(7),
        ]);

        // Auth::setUser($user);

        return (new ApiTokenResource($apiToken))
            ->additional([
                'success' => true,
                'message' => 'Login successfully',
                'token' => $tokenString,
            ]);
    }
}
