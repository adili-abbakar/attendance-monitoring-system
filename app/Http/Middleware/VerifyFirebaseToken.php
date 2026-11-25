<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\FirebaseAuthService;
use App\Models\User;
use App\Models\Lecturer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class VerifyFirebaseToken
{
    public function __construct(
        private FirebaseAuthService $firebaseAuthService
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');
        
        if (!$authHeader) {
            return $this->unauthorized('Missing Authorization header');
        }

        if (!str_starts_with($authHeader, 'Bearer ')) {
            return $this->unauthorized('Invalid Authorization header format. Expected: Bearer <token>');
        }

        $idToken = substr($authHeader, 7);

        try {
            $verifiedToken = $this->firebaseAuthService->verifyIdToken($idToken);
            
            $user = $this->firebaseAuthService->getUserByFirebaseUid($verifiedToken['uid']);
            
            if (!$user) {
                $user = DB::transaction(function () use ($verifiedToken) {
                    return User::create([
                        'name' => $verifiedToken['name'] ?? $verifiedToken['email'] ?? 'Lecturer',
                        'email' => $verifiedToken['email'],
                        'password' => null,
                        'role' => 'lecturer',
                        'status' => true,
                        'firebase_uid' => $verifiedToken['uid'],
                        'email_verified_at' => $verifiedToken['email_verified'] ? now() : null,
                    ]);
                });

                Lecturer::create([
                    'user_id' => $user->id,
                    'record_uuid' => Str::uuid()->toString(),
                    'staff_number' => null,
                    'schema_version' => 1,
                    'change_version' => 1,
                ]);
            }

            if (!$user->status) {
                return $this->forbidden('User account is inactive');
            }

            if ($user->role !== 'lecturer') {
                return $this->forbidden('Lecturer role required');
            }

            $lecturer = $user->lecturer;
            
            if (!$lecturer) {
                $lecturer = DB::transaction(function () use ($user) {
                    return Lecturer::create([
                        'user_id' => $user->id,
                        'record_uuid' => Str::uuid()->toString(),
                        'staff_number' => null,
                        'schema_version' => 1,
                        'change_version' => 1,
                    ]);
                });
            }

            $request->attributes->set('firebase_uid', $verifiedToken['uid']);
            $request->attributes->set('firebase_claims', $verifiedToken);
            $request->attributes->set('auth_user', $user);
            $request->attributes->set('auth_lecturer', $lecturer);

            return $next($request);
        } catch (\InvalidArgumentException $e) {
            return $this->unauthorized($e->getMessage());
        } catch (\Exception $e) {
            return $this->unauthorized('Token verification failed: ' . $e->getMessage());
        }
    }

    private function unauthorized(string $message): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 'unauthenticated',
                'message' => $message,
            ],
        ], 401);
    }

    private function forbidden(string $message): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => 'permission-denied',
                'message' => $message,
            ],
        ], 403);
    }
}