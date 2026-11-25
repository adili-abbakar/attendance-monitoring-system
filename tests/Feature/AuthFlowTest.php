<?php

use App\Models\User;
use App\Models\Lecturer;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

describe('Sanctum Authentication Flow', function () {

    describe('POST /api/v1/auth/login', function () {

        test('returns token and user data for valid credentials', function () {
            $user = User::factory()->lecturer()->create([
                'email' => 'lecturer@example.com',
                'password' => Hash::make('password123'),
                'name' => 'Dr. Lecturer',
            ]);
            $lecturer = Lecturer::factory()->create([
                'user_id' => $user->id,
                'staff_number' => 'STF001',
            ]);

            $response = $this->postJson('/api/v1/auth/login', [
                'email' => 'lecturer@example.com',
                'password' => 'password123',
            ]);

            $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        'access_token',
                        'token_type',
                        'expires_at',
                        'user' => [
                            'id',
                            'name',
                            'email',
                            'role',
                        ],
                        'lecturer' => [
                            'user_id',
                            'record_uuid',
                            'staff_number',
                        ],
                    ],
                ]);
            $this->assertEquals('success', $response->json('status'));
            $this->assertEquals('Bearer', $response->json('data.token_type'));
        });

        test('returns 422 for missing credentials', function () {
            $response = $this->postJson('/api/v1/auth/login', []);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['email', 'password']);
        });

        test('returns 401 for invalid password', function () {
            $user = User::factory()->lecturer()->create([
                'email' => 'lecturer@example.com',
                'password' => Hash::make('password123'),
            ]);
            Lecturer::factory()->create(['user_id' => $user->id]);

            $response = $this->postJson('/api/v1/auth/login', [
                'email' => 'lecturer@example.com',
                'password' => 'wrongpassword',
            ]);

            $response->assertStatus(401)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'Invalid credentials.',
                ]);
        });

        test('returns 401 for non-existent user', function () {
            $response = $this->postJson('/api/v1/auth/login', [
                'email' => 'nonexistent@example.com',
                'password' => 'password123',
            ]);

            $response->assertStatus(401)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'Invalid credentials.',
                ]);
        });

        test('returns 403 for inactive account', function () {
            $user = User::factory()->lecturer()->create([
                'email' => 'lecturer@example.com',
                'password' => Hash::make('password123'),
                'status' => false,
            ]);
            Lecturer::factory()->create(['user_id' => $user->id]);

            $response = $this->postJson('/api/v1/auth/login', [
                'email' => 'lecturer@example.com',
                'password' => 'password123',
            ]);

            $response->assertStatus(403)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'Account is inactive.',
                ]);
        });

        test('returns 403 for non-lecturer role', function () {
            $user = User::factory()->student()->create([
                'email' => 'student@example.com',
                'password' => Hash::make('password123'),
            ]);

            $response = $this->postJson('/api/v1/auth/login', [
                'email' => 'student@example.com',
                'password' => 'password123',
            ]);

            $response->assertStatus(403)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'Lecturer role required.',
                ]);
        });

        test('returns 403 for lecturer without profile', function () {
            $user = User::factory()->lecturer()->create([
                'email' => 'lecturer@example.com',
                'password' => Hash::make('password123'),
            ]);
            // No Lecturer profile created

            $response = $this->postJson('/api/v1/auth/login', [
                'email' => 'lecturer@example.com',
                'password' => 'password123',
            ]);

            $response->assertStatus(403)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'Lecturer profile not found.',
                ]);
        });

        test('rate limits after 5 failed attempts', function () {
            $user = User::factory()->lecturer()->create([
                'email' => 'lecturer@example.com',
                'password' => Hash::make('password123'),
            ]);
            Lecturer::factory()->create(['user_id' => $user->id]);

            // Make 5 failed attempts
            for ($i = 0; $i < 5; $i++) {
                $response = $this->postJson('/api/v1/auth/login', [
                    'email' => 'lecturer@example.com',
                    'password' => 'wrongpassword',
                ]);
                $response->assertStatus(401);
            }

            // 6th attempt should be rate limited
            $response = $this->postJson('/api/v1/auth/login', [
                'email' => 'lecturer@example.com',
                'password' => 'wrongpassword',
            ]);

            $response->assertStatus(429)
                ->assertJsonStructure([
                    'status',
                    'message',
                ]);
        });
    });

    describe('POST /api/v1/auth/logout', function () {

        test('logs out authenticated user', function () {
            $user = User::factory()->lecturer()->create();
            Lecturer::factory()->create(['user_id' => $user->id]);
            $token = $user->createToken('test-token')->plainTextToken;

            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->postJson('/api/v1/auth/logout');

            $response->assertStatus(200)
                ->assertJson([
                    'status' => 'success',
                    'message' => 'Logged out successfully.',
                ]);

            // Token should be deleted
            $this->assertEquals(0, $user->tokens()->count());
        });

        test('returns 401 for unauthenticated request', function () {
            $response = $this->postJson('/api/v1/auth/logout');

            $response->assertStatus(401);
        });
    });
});