<?php

use App\Models\User;
use App\Models\Lecturer;
use App\Models\Course;
use App\Models\Level;
use App\Models\AcademicSession;
use App\Models\LectureSession;
use App\Models\AttendanceRecord;
use App\Models\Enrolment;
use App\Models\SyncDevice;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;

function makeLecturerUser(string $email = null): array
{
    $user = User::factory()->create([
        'role' => 'lecturer',
        'status' => true,
        'email' => $email ?? 'lecturer-' . Str::random(8) . '@example.com',
        'name' => 'Test Lecturer',
        'password' => bcrypt('password'),
    ]);
    $lecturer = Lecturer::factory()->create(['user_id' => $user->id]);

    return ['user' => $user, 'lecturer' => $lecturer];
}

function makeLecturerUserWithToken(string $email = null): array
{
    $data = makeLecturerUser($email);
    $token = $data['user']->createToken('test-token')->plainTextToken;

    return ['user' => $data['user'], 'lecturer' => $data['lecturer'], 'token' => $token];
}

function authHeaders(string $token): array
{
    return ['Authorization' => 'Bearer ' . $token];
}

describe('SyncController - upsertCourse', function () {

    test('creates a new course with source_revision 1', function () {
        $data = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();

        $courseId = Str::uuid()->toString();

        $response = $this->withHeaders(authHeaders($data['token']))
            ->postJson('/api/v1/sync/upsert-course', [
                'course_id' => $courseId,
                'record_uuid' => $courseId,
                'code' => 'CS101',
                'title' => 'Introduction to Computer Science',
                'level_id' => $level->record_uuid,
                'academic_session_id' => $session->record_uuid,
                'semester' => 1,
                'is_active' => true,
                'source_revision' => 1,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'course_id' => $courseId,
            'created' => true,
            'source_revision' => 1,
            'idempotent_replay' => false,
        ]);

        $this->assertDatabaseHas('courses', [
            'record_uuid' => $courseId,
            'code' => 'CS101',
            'title' => 'Introduction to Computer Science',
            'lecturer_id' => $data['user']->id,
            'change_version' => 1,
        ]);

        $this->assertDatabaseHas('change_feed', [
            'collection_name' => 'courses',
            'operation' => 'create',
            'lecturer_id' => $data['user']->id,
        ]);
    });

    test('rejects create with source_revision != 1', function () {
        $data = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();

        $response = $this->withHeaders(authHeaders($data['token']))
            ->postJson('/api/v1/sync/upsert-course', [
                'course_id' => Str::uuid()->toString(),
                'record_uuid' => Str::uuid()->toString(),
                'code' => 'CS101',
                'title' => 'Intro to CS',
                'level_id' => $level->record_uuid,
                'academic_session_id' => $session->record_uuid,
                'semester' => 1,
                'is_active' => true,
                'source_revision' => 2,
            ]);

        $response->assertStatus(400);
    });

    test('updates existing course with incrementing source_revision', function () {
        $data = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();

        $course = Course::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'code' => 'CS101',
            'title' => 'Original Title',
            'level_id' => $level->id,
            'academic_session_id' => $session->id,
            'lecturer_id' => $data['user']->id,
            'change_version' => 1,
        ]);

        $response = $this->withHeaders(authHeaders($data['token']))
            ->postJson('/api/v1/sync/upsert-course', [
                'course_id' => $course->record_uuid,
                'record_uuid' => $course->record_uuid,
                'code' => 'CS101',
                'title' => 'Updated Title',
                'level_id' => $level->record_uuid,
                'academic_session_id' => $session->record_uuid,
                'semester' => 2,
                'is_active' => true,
                'source_revision' => 2,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'course_id' => $course->record_uuid,
            'created' => false,
            'source_revision' => 2,
            'idempotent_replay' => false,
        ]);

        $this->assertDatabaseHas('courses', [
            'record_uuid' => $course->record_uuid,
            'title' => 'Updated Title',
            'change_version' => 2,
        ]);
    });

    test('returns idempotent replay when source_revision matches current change_version', function () {
        $data = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();

        $course = Course::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'code' => 'CS101',
            'title' => 'Existing Course',
            'level_id' => $level->id,
            'academic_session_id' => $session->id,
            'lecturer_id' => $data['user']->id,
            'change_version' => 3,
        ]);

        $response = $this->withHeaders(authHeaders($data['token']))
            ->postJson('/api/v1/sync/upsert-course', [
                'course_id' => $course->record_uuid,
                'record_uuid' => $course->record_uuid,
                'code' => 'CS101',
                'title' => 'Existing Course',
                'level_id' => $level->record_uuid,
                'academic_session_id' => $session->record_uuid,
                'semester' => 1,
                'is_active' => true,
                'source_revision' => 3,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'idempotent_replay' => true,
            'created' => false,
        ]);
    });

    test('rejects update from wrong lecturer', function () {
        $owner = makeLecturerUserWithToken();
        $attacker = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();

        $course = Course::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'code' => 'CS101',
            'title' => 'Victim Course',
            'level_id' => $level->id,
            'academic_session_id' => $session->id,
            'lecturer_id' => $owner['user']->id,
            'change_version' => 1,
        ]);

        $response = $this->withHeaders(authHeaders($attacker['token']))
            ->postJson('/api/v1/sync/upsert-course', [
                'course_id' => $course->record_uuid,
                'record_uuid' => $course->record_uuid,
                'code' => 'CS101',
                'title' => 'Hacked Title',
                'level_id' => $level->record_uuid,
                'academic_session_id' => $session->record_uuid,
                'semester' => 1,
                'is_active' => true,
                'source_revision' => 2,
            ]);

        $response->assertStatus(403);
    });
});

describe('SyncController - upsertLectureSession', function () {

    test('creates a new lecture session', function () {
        $data = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();
        $course = Course::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'code' => 'CS101',
            'title' => 'Intro to CS',
            'level_id' => $level->id,
            'academic_session_id' => $session->id,
            'lecturer_id' => $data['user']->id,
        ]);

        $recordUuid = Str::uuid()->toString();

        $response = $this->withHeaders(authHeaders($data['token']))
            ->postJson('/api/v1/sync/upsert-lecture-session', [
                'record_uuid' => $recordUuid,
                'course_id' => $course->record_uuid,
                'session_number' => 1,
                'week_number' => 1,
                'lecture_date' => '2026-01-15',
                'from_time' => '09:00',
                'to_time' => '10:00',
                'duration_minutes' => 60,
                'status' => 'scheduled',
                'source_revision' => 1,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'record_uuid' => $recordUuid,
            'created' => true,
            'source_revision' => 1,
        ]);

        $this->assertDatabaseHas('lecture_sessions', [
            'record_uuid' => $recordUuid,
            'course_id' => $course->id,
            'lecturer_id' => $data['user']->id,
            'session_number' => 1,
        ]);
    });

    test('rejects lecture session for non-existent course', function () {
        $data = makeLecturerUserWithToken();

        $response = $this->withHeaders(authHeaders($data['token']))
            ->postJson('/api/v1/sync/upsert-lecture-session', [
                'record_uuid' => Str::uuid()->toString(),
                'course_id' => Str::uuid()->toString(),
                'session_number' => 1,
                'week_number' => 1,
                'lecture_date' => '2026-01-15',
                'from_time' => '09:00',
                'to_time' => '10:00',
                'duration_minutes' => 60,
                'status' => 'scheduled',
                'source_revision' => 1,
            ]);

        $response->assertStatus(400);
    });
});

describe('SyncController - createAttendanceRecord', function () {

    test('creates a new attendance record', function () {
        $data = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();
        $course = Course::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'code' => 'CS101',
            'title' => 'Intro to CS',
            'level_id' => $level->id,
            'academic_session_id' => $session->id,
            'lecturer_id' => $data['user']->id,
        ]);
        $lectureSession = LectureSession::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'course_id' => $course->id,
            'lecturer_id' => $data['user']->id,
        ]);
        $studentUser = User::factory()->student()->create();
        $student = \App\Models\Student::factory()->create([
            'user_id' => $studentUser->id,
            'record_uuid' => Str::uuid(),
        ]);

        $recordUuid = Str::uuid()->toString();

        $response = $this->withHeaders(authHeaders($data['token']))
            ->postJson('/api/v1/sync/create-attendance-record', [
                'record_uuid' => $recordUuid,
                'lecture_session_id' => $lectureSession->record_uuid,
                'student_id' => $studentUser->record_uuid,
                'status' => 'present',
                'scanned_at' => '2026-01-15 09:05:00',
                'source_revision' => 1,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'record_uuid' => $recordUuid,
            'created' => true,
        ]);

        $this->assertDatabaseHas('attendance_records', [
            'record_uuid' => $recordUuid,
            'status' => 'present',
            'lecturer_id' => $data['user']->id,
        ]);
    });

    test('rejects attendance record for non-existent student', function () {
        $data = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();
        $course = Course::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'code' => 'CS101',
            'title' => 'Intro to CS',
            'level_id' => $level->id,
            'academic_session_id' => $session->id,
            'lecturer_id' => $data['user']->id,
        ]);
        $lectureSession = LectureSession::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'course_id' => $course->id,
            'lecturer_id' => $data['user']->id,
        ]);

        $response = $this->withHeaders(authHeaders($data['token']))
            ->postJson('/api/v1/sync/create-attendance-record', [
                'record_uuid' => Str::uuid()->toString(),
                'lecture_session_id' => $lectureSession->record_uuid,
                'student_id' => Str::uuid()->toString(),
                'status' => 'present',
                'scanned_at' => '2026-01-15 09:05:00',
                'source_revision' => 1,
            ]);

        $response->assertStatus(400);
    });
});

describe('SyncController - deleteCourse', function () {

    test('deletes a course owned by the lecturer', function () {
        $data = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();
        $course = Course::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'code' => 'CS101',
            'title' => 'To Be Deleted',
            'level_id' => $level->id,
            'academic_session_id' => $session->id,
            'lecturer_id' => $data['user']->id,
        ]);

        $response = $this->withHeaders(authHeaders($data['token']))
            ->deleteJson('/api/v1/sync/delete-course', [
                'course_id' => $course->record_uuid,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'deleted' => true,
        ]);

        $this->assertDatabaseMissing('courses', ['record_uuid' => $course->record_uuid]);
        $this->assertDatabaseHas('change_feed', [
            'collection_name' => 'courses',
            'operation' => 'delete',
            'lecturer_id' => $data['user']->id,
        ]);
    });

    test('returns 404 for non-existent course', function () {
        $data = makeLecturerUserWithToken();

        $response = $this->withHeaders(authHeaders($data['token']))
            ->deleteJson('/api/v1/sync/delete-course', [
                'course_id' => Str::uuid()->toString(),
            ]);

        $response->assertStatus(404);
    });
});

describe('SyncController - deleteLectureSession', function () {

    test('deletes a lecture session owned by the lecturer', function () {
        $data = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();
        $course = Course::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'code' => 'CS101',
            'title' => 'Intro to CS',
            'level_id' => $level->id,
            'academic_session_id' => $session->id,
            'lecturer_id' => $data['user']->id,
        ]);
        $lectureSession = LectureSession::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'course_id' => $course->id,
            'lecturer_id' => $data['user']->id,
        ]);

        $response = $this->withHeaders(authHeaders($data['token']))
            ->deleteJson('/api/v1/sync/delete-lecture-session', [
                'record_uuid' => $lectureSession->record_uuid,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'deleted' => true,
        ]);

        $this->assertDatabaseMissing('lecture_sessions', ['record_uuid' => $lectureSession->record_uuid]);
    });
});

describe('SyncController - deleteAttendanceRecord', function () {

    test('deletes an attendance record owned by the lecturer', function () {
        $data = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();
        $course = Course::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'code' => 'CS101',
            'title' => 'Intro to CS',
            'level_id' => $level->id,
            'academic_session_id' => $session->id,
            'lecturer_id' => $data['user']->id,
        ]);
        $lectureSession = LectureSession::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'course_id' => $course->id,
            'lecturer_id' => $data['user']->id,
        ]);
        $attendanceRecord = AttendanceRecord::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'lecture_session_id' => $lectureSession->id,
            'student_id' => 1,
            'lecturer_id' => $data['user']->id,
        ]);

        $response = $this->withHeaders(authHeaders($data['token']))
            ->deleteJson('/api/v1/sync/delete-attendance-record', [
                'record_uuid' => $attendanceRecord->record_uuid,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'deleted' => true,
        ]);

        $this->assertDatabaseMissing('attendance_records', ['record_uuid' => $attendanceRecord->record_uuid]);
    });
});

describe('SyncController - enrolStudent', function () {

    test('enrols a student in a course', function () {
        $data = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();
        $course = Course::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'code' => 'CS101',
            'title' => 'Intro to CS',
            'level_id' => $level->id,
            'academic_session_id' => $session->id,
            'lecturer_id' => $data['user']->id,
        ]);
        $studentUser = User::factory()->student()->create();
        $student = \App\Models\Student::factory()->create([
            'user_id' => $studentUser->id,
            'record_uuid' => Str::uuid()->toString(),
        ]);

        $recordUuid = Str::uuid()->toString();

        $response = $this->withHeaders(authHeaders($data['token']))
            ->postJson('/api/v1/sync/enrol-student', [
                'record_uuid' => $recordUuid,
                'course_id' => $course->record_uuid,
                'student_id' => $studentUser->record_uuid,
                'source_revision' => 1,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'record_uuid' => $recordUuid,
            'created' => true,
        ]);

        $this->assertDatabaseHas('enrolments', [
            'record_uuid' => $recordUuid,
            'course_id' => $course->id,
            'student_id' => $studentUser->id,
            'lecturer_id' => $data['user']->id,
        ]);
    });

    test('rejects enrolment for non-existent student', function () {
        $data = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();
        $course = Course::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'code' => 'CS101',
            'title' => 'Intro to CS',
            'level_id' => $level->id,
            'academic_session_id' => $session->id,
            'lecturer_id' => $data['user']->id,
        ]);

        $response = $this->withHeaders(authHeaders($data['token']))
            ->postJson('/api/v1/sync/enrol-student', [
                'record_uuid' => Str::uuid()->toString(),
                'course_id' => $course->record_uuid,
                'student_id' => Str::uuid()->toString(),
                'source_revision' => 1,
            ]);

        $response->assertStatus(400);
    });
});

describe('SyncController - removeEnrolment', function () {

    test('removes an enrolment owned by the lecturer', function () {
        $data = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();
        $course = Course::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'code' => 'CS101',
            'title' => 'Intro to CS',
            'level_id' => $level->id,
            'academic_session_id' => $session->id,
            'lecturer_id' => $data['user']->id,
        ]);
        $enrolment = Enrolment::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'course_id' => $course->id,
            'student_id' => 1,
            'lecturer_id' => $data['user']->id,
        ]);

        $response = $this->withHeaders(authHeaders($data['token']))
            ->deleteJson('/api/v1/sync/remove-enrolment', [
                'record_uuid' => $enrolment->record_uuid,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'removed' => true,
        ]);

        $this->assertDatabaseMissing('enrolments', ['record_uuid' => $enrolment->record_uuid]);
    });
});

describe('SyncController - registerDevice', function () {

    test('registers a new sync device', function () {
        $data = makeLecturerUserWithToken();
        $deviceId = Str::uuid()->toString();

        $response = $this->withHeaders(authHeaders($data['token']))
            ->postJson('/api/v1/sync/register-device', [
                'device_id' => $deviceId,
                'device_info' => 'iPhone 15 Pro',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'device_id' => $deviceId,
            'registered' => true,
        ]);

        $this->assertDatabaseHas('sync_devices', [
            'device_id' => $deviceId,
            'lecturer_id' => $data['user']->id,
        ]);
    });
});

describe('SyncController - advanceCheckpoint', function () {

    test('advances sync checkpoint for a device', function () {
        $data = makeLecturerUserWithToken();
        $deviceId = Str::uuid()->toString();
        $cursor = Str::uuid()->toString();

        SyncDevice::create([
            'device_id' => $deviceId,
            'lecturer_id' => $data['user']->id,
            'schema_version' => 1,
        ]);

        $response = $this->withHeaders(authHeaders($data['token']))
            ->postJson('/api/v1/sync/advance-checkpoint', [
                'device_id' => $deviceId,
                'collection_name' => 'courses',
                'cursor' => $cursor,
                'last_page_id' => null,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'advanced' => true,
        ]);

        $device = SyncDevice::where('device_id', $deviceId)->first();
        $this->assertDatabaseHas('sync_checkpoints', [
            'device_id' => $device->id,
            'collection_name' => 'courses',
            'cursor' => $cursor,
        ]);
    });
});

describe('SyncController - getChangeFeed', function () {

    test('returns change feed entries for the lecturer', function () {
        $data = makeLecturerUserWithToken();
        $level = Level::factory()->create();
        $session = AcademicSession::factory()->create();
        Course::factory()->create([
            'record_uuid' => Str::uuid()->toString(),
            'code' => 'CS101',
            'title' => 'Intro to CS',
            'level_id' => $level->id,
            'academic_session_id' => $session->id,
            'lecturer_id' => $data['user']->id,
        ]);

        $response = $this->withHeaders(authHeaders($data['token']))
            ->getJson('/api/v1/sync/change-feed', [
                'limit' => 10,
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'entries',
            'count',
        ]);
    });

    test('filters change feed by collection_name', function () {
        $data = makeLecturerUserWithToken();

        $response = $this->withHeaders(authHeaders($data['token']))
            ->getJson('/api/v1/sync/change-feed', [
                'collection_name' => 'courses',
            ]);

        $response->assertStatus(200);
    });
});

describe('SyncController - advanceChangeFeedCheckpoint', function () {

    test('advances change feed checkpoint', function () {
        $data = makeLecturerUserWithToken();

        $response = $this->withHeaders(authHeaders($data['token']))
            ->postJson('/api/v1/sync/advance-change-feed-checkpoint', [
                'collection_name' => 'courses',
                'sequence' => 42,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'advanced' => true,
            'collection_name' => 'courses',
            'sequence' => 42,
        ]);
    });
});