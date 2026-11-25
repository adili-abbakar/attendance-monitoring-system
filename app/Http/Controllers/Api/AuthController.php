<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FirebaseAuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private FirebaseAuthService $firebaseAuthService
    ) {}

    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);

        try {
            $verifiedToken = $this->firebaseAuthService->verifyIdToken($request->string('id_token'));
            $user = $this->firebaseAuthService->getUserByFirebaseUid($verifiedToken['uid']);

            if (!$user) {
                return response()->json([
                    'error' => [
                        'code' => 'not-found',
                        'message' => 'User not registered in Laravel backend',
                    ],
                ], 404);
            }

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'firebase_uid' => $user->firebase_uid,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'lecturer' => $user->lecturer ? [
                    'user_id' => $user->lecturer->user_id,
                    'record_uuid' => $user->lecturer->record_uuid,
                    'staff_number' => $user->lecturer->staff_number,
                ] : null,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'error' => [
                    'code' => 'unauthenticated',
                    'message' => $e->getMessage(),
                ],
            ], 401);
        }
    }
}