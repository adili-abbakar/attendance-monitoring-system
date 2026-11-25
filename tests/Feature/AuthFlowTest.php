<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SyncController;
use App\Models\User;
use App\Models\Lecturer;
use App\Models\Course;
use App\Models\Level;
use App\Models\AcademicSession;
use App\Services\FirebaseAuthService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

beforeEach(function () {
    $this->firebaseAuthService = Mockery::mock(FirebaseAuthService::class);
    $this->app->instance(FirebaseAuthService::class, $this->firebaseAuthService);
});

describe('Firebase Authentication Flow', function () {

    describe('POST /api/v1/auth/verify', function () {

        test('returns user and lecturer data for valid token with existing user', function () {
            $user = User::factory()->create([
                'role' => 'lecturer',
                'status' => true,
                'firebase_uid' => 'firebase-uid-123',
                'email' => 'lecturer@example.com',
                'name' => 'Dr. Lecturer',
            ]);
            $lecturer = Lecturer::factory()->create([
                'user_id' => $user->id,
                'staff_number' => 'STF001',
            ]);

            $this->firebaseAuthService
                ->shouldReceive('verifyIdToken')
                ->once()
                ->with('valid-token')
                ->andReturn([
                    'uid' => 'firebase-uid-123',
                    'email' => 'lecturer@example.com',
                    'email_verified' => true,
                    'sign_in_provider' => 'password',
                    'iat' => 1234567890,
                    'exp' => 9999999999,
                ]);
            $this->firebaseAuthService
                ->shouldReceive('getUserByFirebaseUid')
                ->once()
                ->with('firebase-uid-123')
                ->andReturn($user);

            $response = $this->postJson('/api/v1/auth/verify', [
                'id_token' => 'valid-token',
            ]);

            $response->assertStatus(200);
            $response->assertJson([
                'user' => [
                    'id' => $user->id,
                    'firebase_uid' => 'firebase-uid-123',
                    'name' => 'Dr. Lecturer',
                    'email' => 'lecturer@example.com',
                    'role' => 'lecturer',
                ],
                'lecturer' => [
                    'user_id' => $lecturer->user_id,
                    'record_uuid' => $lecturer->record_uuid,
                    'staff_number' => 'STF001',
                ],
            ]);
        });

        test('returns 404 for valid token but user not registered in Laravel', function () {
            $this->firebaseAuthService
                ->shouldReceive('verifyIdToken')
                ->once()
                ->with('valid-token')
                ->andReturn([
                    'uid' => 'unknown-uid',
                    'email' => 'unknown@example.com',
                    'email_verified' => true,
                    'sign_in_provider' => 'password',
                ]);
            $this->firebaseAuthService
                ->shouldReceive('getUserByFirebaseUid')
                ->once()
                ->with('unknown-uid')
                ->andReturn(null);

            $response = $this->postJson('/api/v1/auth/verify', [
                'id_token' => 'valid-token',
            ]);

            $response->assertStatus(404);
            $response->assertJson([
                'error' => [
                    'code' => 'not-found',
                ],
            ]);
        });

        test('returns 401 for invalid token', function () {
            $this->firebaseAuthService
                ->shouldReceive('verifyIdToken')
                ->once()
                ->with('invalid-token')
                ->andThrow(new \InvalidArgumentException('Firebase ID token verification failed'));

            $response = $this->postJson('/api/v1/auth/verify', [
                'id_token' => 'invalid-token',
            ]);

            $response->assertStatus(401);
            $response->assertJson([
                'error' => [
                    'code' => 'unauthenticated',
                ],
            ]);
        });

        test('returns 422 for missing id_token', function () {
            $response = $this->postJson('/api/v1/auth/verify', []);

            $response->assertStatus(422);
        });

        test('rejects non-password sign-in provider', function () {
            $this->firebaseAuthService
                ->shouldReceive('verifyIdToken')
                ->once()
                ->with('google-token')
                ->andThrow(new \InvalidArgumentException('Email/Password authentication is required'));

            $response = $this->postJson('/api/v1/auth/verify', [
                'id_token' => 'google-token',
            ]);

            $response->assertStatus(401);
        });
    });
});

describe('Firebase Middleware - Auto Resolution', function () {

    test('auto-creates User and Lecturer when Firebase UID not found in Laravel DB', function () {
        $verifiedToken = [
            'uid' => 'new-firebase-uid',
            'email' => 'newlecturer@example.com',
            'email_verified' => true,
            'sign_in_provider' => 'password',
            'iat' => 1234567890,
            'exp' => 9999999999,
        ];

        $this->firebaseAuthService
            ->shouldReceive('verifyIdToken')
            ->once()
            ->with('new-token')
            ->andReturn($verifiedToken);

        $this->assertDatabaseMissing('users', ['firebase_uid' => 'new-firebase-uid']);

        $response = $this->withHeader('Authorization', 'Bearer new-token')
            ->postJson('/api/v1/sync/upsert-course', [
                'course_id' => Str::uuid()->toString(),
                'record_uuid' => Str::uuid()->toString(),
                'code' => 'CS101',
                'title' => 'Introduction to Computer Science',
                'level_id' => Str::uuid()->toString(),
                'academic_session_id' => Str::uuid()->toString(),
                'semester' => 1,
                'is_active' => true,
                'source_revision' => 1,
            ]);

        // Level and academic session don't exist, so we get 400
        // But the user should have been auto-created
        $this->assertDatabaseHas('users', [
            'firebase_uid' => 'new-firebase-uid',
            'email' => 'newlecturer@example.com',
            'role' => 'lecturer',
            'status' => true,
        ]);
        $this->assertDatabaseHas('lecturers', [
            'user_id' => User::where('firebase_uid', 'new-firebase-uid')->first()->id,
        ]);
    });

    test('auto-creates Lecturer when User exists but Lecturer profile missing', function () {
        $user = User::factory()->create([
            'role' => 'lecturer',
            'status' => true,
            'firebase_uid' => 'existing-uid',
            'email' => 'existing@example.com',
        ]);

        $verifiedToken = [
            'uid' => 'existing-uid',
            'email' => 'existing@example.com',
            'email_verified' => true,
            'sign_in_provider' => 'password',
        ];

        $this->firebaseAuthService
            ->shouldReceive('verifyIdToken')
            ->once()
            ->with('existing-token')
            ->andReturn($verifiedToken);

        $this->assertDatabaseMissing('lecturers', ['user_id' => $user->id]);

        $response = $this->withHeader('Authorization', 'Bearer existing-token')
            ->postJson('/api/v1/sync/upsert-course', [
                'course_id' => Str::uuid()->toString(),
                'record_uuid' => Str::uuid()->toString(),
                'code' => 'CS101',
                'title' => 'Intro to CS',
                'level_id' => Str::uuid()->toString(),
                'academic_session_id' => Str::uuid()->toString(),
                'semester' => 1,
                'is_active' => true,
                'source_revision' => 1,
            ]);

        $this->assertDatabaseHas('lecturers', [
            'user_id' => $user->id,
        ]);
    });

    test('rejects inactive user account', function () {
        $user = User::factory()->create([
            'role' => 'lecturer',
            'status' => false,
            'firebase_uid' => 'inactive-uid',
        ]);

        $verifiedToken = [
            'uid' => 'inactive-uid',
            'email' => 'inactive@example.com',
            'sign_in_provider' => 'password',
        ];

        $this->firebaseAuthService
            ->shouldReceive('verifyIdToken')
            ->once()
            ->with('inactive-token')
            ->andReturn($verifiedToken);

        $response = $this->withHeader('Authorization', 'Bearer inactive-token')
            ->postJson('/api/v1/sync/upsert-course', [
                'course_id' => Str::uuid()->toString(),
                'record_uuid' => Str::uuid()->toString(),
                'code' => 'CS101',
                'title' => 'Intro to CS',
                'level_id' => Str::uuid()->toString(),
                'academic_session_id' => Str::uuid()->toString(),
                'semester' => 1,
                'is_active' => true,
                'source_revision' => 1,
            ]);

        $response->assertStatus(403);
    });

    test('rejects non-lecturer role', function () {
        $user = User::factory()->create([
            'role' => 'student',
            'status' => true,
            'firebase_uid' => 'student-uid',
        ]);

        $verifiedToken = [
            'uid' => 'student-uid',
            'email' => 'student@example.com',
            'sign_in_provider' => 'password',
        ];

        $this->firebaseAuthService
            ->shouldReceive('verifyIdToken')
            ->once()
            ->with('student-token')
            ->andReturn($verifiedToken);

        $response = $this->withHeader('Authorization', 'Bearer student-token')
            ->postJson('/api/v1/sync/upsert-course', [
                'course_id' => Str::uuid()->toString(),
                'record_uuid' => Str::uuid()->toString(),
                'code' => 'CS101',
                'title' => 'Intro to CS',
                'level_id' => Str::uuid()->toString(),
                'academic_session_id' => Str::uuid()->toString(),
                'semester' => 1,
                'is_active' => true,
                'source_revision' => 1,
            ]);

        $response->assertStatus(403);
    });

    test('returns 401 for missing Authorization header', function () {
        $response = $this->postJson('/api/v1/sync/upsert-course', []);

        $response->assertStatus(401);
    });

    test('returns 401 for invalid Authorization header format', function () {
        $response = $this->withHeader('Authorization', 'InvalidFormat')
            ->postJson('/api/v1/sync/upsert-course', []);

        $response->assertStatus(401);
    });
});

describe('Data Isolation - Lecturer Access Control', function () {

    test('lecturer can only access their own courses', function () {
        $lecturerUser = User::factory()->create([
            'role' => 'lecturer',
            'status' => true,
            'firebase_uid' => 'lecturer-uid-1',
        ]);
        $lecturer = Lecturer::factory()->create(['user_id' => $lecturerUser->id]);

        $otherUser = User::factory()->create([
            'role' => 'lecturer',
            'status' => true,
            'firebase_uid' => 'lecturer-uid-2',
        ]);
        $otherLecturer = Lecturer::factory()->create(['user_id' => $otherUser->id]);

        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();

        $otherCourse = Course::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'code' => 'OTHER101',
            'title' => 'Other Course',
            'level_id' => $level->id,
            'academic_session_id' => $session->id,
            'lecturer_id' => $otherUser->id,
            'change_version' => 1,
        ]);

        $verifiedToken = [
            'uid' => 'lecturer-uid-1',
            'email' => 'lecturer1@example.com',
            'sign_in_provider' => 'password',
        ];

        $this->firebaseAuthService
            ->shouldReceive('verifyIdToken')
            ->once()
            ->with('lecturer-token-1')
            ->andReturn($verifiedToken);

        // Try to update another lecturer's course - should fail ownership check
        $response = $this->withHeader('Authorization', 'Bearer lecturer-token-1')
            ->postJson('/api/v1/sync/upsert-course', [
                'course_id' => $otherCourse->record_uuid,
                'record_uuid' => $otherCourse->record_uuid,
                'code' => 'OTHER101',
                'title' => 'Modified Title',
                'level_id' => $level->record_uuid,
                'academic_session_id' => $session->record_uuid,
                'semester' => 1,
                'is_active' => true,
                'source_revision' => 2,
            ]);

        $response->assertStatus(400);
        $response->assertJson([
            'error' => [
                'code' => 'invalid-argument',
            ],
        ]);
    });
});