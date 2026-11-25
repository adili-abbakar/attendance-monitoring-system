<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $key = 'login:' . $request->ip() . ':' . $request->email;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'status' => 'error',
                'message' => 'Too many login attempts. Please try again in ' . $seconds . ' seconds.',
            ], 429);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($key, 300);
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ], 401);
        }

        if (!$user->status) {
            RateLimiter::hit($key, 300);
            return response()->json([
                'status' => 'error',
                'message' => 'Account is inactive.',
            ], 403);
        }

        if ($user->role !== 'lecturer') {
            RateLimiter::hit($key, 300);
            return response()->json([
                'status' => 'error',
                'message' => 'Lecturer role required.',
            ], 403);
        }

        $lecturer = $user->lecturer;

        if (!$lecturer) {
            RateLimiter::hit($key, 300);
            return response()->json([
                'status' => 'error',
                'message' => 'Lecturer profile not found.',
            ], 403);
        }

        RateLimiter::clear($key);

        $token = $user->createToken('api-token', [], now()->addMinutes(config('sanctum.expiration', 43200)))->plainTextToken;

        $expiresAt = now()->addMinutes(config('sanctum.expiration', 43200))->toISOString();

        return response()->json([
            'status' => 'success',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => $expiresAt,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'lecturer' => [
                    'user_id' => $lecturer->user_id,
                    'record_uuid' => $lecturer->record_uuid,
                    'staff_number' => $lecturer->staff_number,
                ],
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully.',
        ]);
    }
}