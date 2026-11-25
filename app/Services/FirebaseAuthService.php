<?php

namespace App\Services;

use Firebase\Auth\Token\Verifier;
use Firebase\Auth\Token\Exception\InvalidToken;
use Firebase\Auth\Token\Exception\ExpiredToken;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth as FirebaseAuth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FirebaseAuthService
{
    private ?FirebaseAuth $auth = null;
    private ?Verifier $verifier = null;
    private string $projectId;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id') ?? env('FIREBASE_PROJECT_ID', 'uniattend-503dd');
        $this->initializeAuth();
    }

    private function initializeAuth(): void
    {
        $credentialsPath = config('services.firebase.credentials_path') ?? env('FIREBASE_CREDENTIALS_PATH');
        
        if ($credentialsPath && file_exists($credentialsPath)) {
            $factory = (new Factory)->withServiceAccount($credentialsPath);
            $this->auth = $factory->createAuth();
            $this->verifier = $this->auth->verifier();
        } else {
            $factory = (new Factory)->withProjectId($this->projectId);
            $this->auth = $factory->createAuth();
            $this->verifier = $this->auth->verifier();
        }
    }

    public function verifyIdToken(string $idToken): array
    {
        try {
            $verifiedToken = $this->verifier->verifyIdToken($idToken);
            
            $claims = $verifiedToken->claims()->all();
            
            if (!isset($claims['sub']) || empty($claims['sub'])) {
                throw new \InvalidArgumentException('Firebase ID token missing UID (sub claim)');
            }

            if (($claims['firebase']['sign_in_provider'] ?? '') !== 'password') {
                throw new \InvalidArgumentException('Email/Password authentication is required');
            }

            return [
                'uid' => $claims['sub'],
                'email' => $claims['email'] ?? null,
                'email_verified' => $claims['email_verified'] ?? false,
                'sign_in_provider' => $claims['firebase']['sign_in_provider'] ?? null,
                'iat' => $claims['iat'] ?? null,
                'exp' => $claims['exp'] ?? null,
            ];
        } catch (ExpiredToken $e) {
            throw new \InvalidArgumentException('Firebase ID token has expired');
        } catch (InvalidToken $e) {
            throw new \InvalidArgumentException('Firebase ID token verification failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Firebase token verification error', ['error' => $e->getMessage()]);
            throw new \InvalidArgumentException('Firebase ID token verification failed');
        }
    }

    public function getUserByFirebaseUid(string $firebaseUid): ?\App\Models\User
    {
        return \App\Models\User::where('firebase_uid', $firebaseUid)->first();
    }

    public function getLecturerByFirebaseUid(string $firebaseUid): ?\App\Models\Lecturer
    {
        $user = $this->getUserByFirebaseUid($firebaseUid);
        return $user?->lecturer;
    }
}