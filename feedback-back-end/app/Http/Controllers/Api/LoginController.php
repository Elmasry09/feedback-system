<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        $email = strtolower($request->email);
        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
        ]);

        $data = [
            'token' => JWTAuth::fromUser($user),
            'user'  => $user
        ];

        return response()->json([
            'status' => 201,
            'message' => 'User registered successfully',
            'data' => $data,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => ['required', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation errors',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (Auth::attempt(['email' => strtolower($request->email), 'password' => $request->password])) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);

            $data = [
                'user'  => $user,
                'token' => $token,
            ];

            return response()->json([
                'status' => 200,
                'message' => 'Login successful',
                'data' => $data,
            ], 200)->withCookie(cookie()->forever('token', $token));
        }

        return response()->json([
            'status' => 401,
            'message' => 'Invalid credentials',
        ], 401);
    }

    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'status' => 200,
            'message' => 'Logout successful',
        ], 200);
    }

    public function refresh(Request $request)
    {
        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());
            return response()->json([
                'status' => 200,
                'message' => 'Token refreshed successfully',
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 401,
                'message' => 'Token refresh failed',
            ], 401);
        }
    }
}
