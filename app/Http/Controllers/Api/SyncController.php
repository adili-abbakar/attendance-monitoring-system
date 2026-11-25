<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Level;
use App\Models\AcademicSession;
use App\Models\LectureSession;
use App\Models\AttendanceRecord;
use App\Models\Enrolment;
use App\Models\Lecturer;
use App\Models\User;
use App\Models\ChangeFeed;
use App\Models\ChangeFeedCounter;
use App\Models\ChangeFeedEventId;
use App\Models\SyncDevice;
use App\Models\SyncCheckpoint;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SyncController extends Controller
{
    protected function getLecturer(Request $request): Lecturer
    {
        $user = $request->user();
        return $user->lecturer;
    }

    protected function getLecturerUser(Request $request): \App\Models\User
    {
        return $request->user();
    }

    public function upsertCourse(Request $request): JsonResponse
    {
        $lecturer = $this->getLecturer($request);
        $lecturerUser = $this->getLecturerUser($request);

        $validated = $request->validate([
            'course_id' => 'required|uuid',
            'record_uuid' => 'required|uuid',
            'code' => 'required|string|max:64',
            'title' => 'required|string|max:200',
            'level_id' => 'required|uuid',
            'academic_session_id' => 'required|uuid',
            'semester' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
            'source_revision' => 'required|integer|min:1',
        ]);

        if ($validated['record_uuid'] !== $validated['course_id']) {
            return response()->json([
                'error' => [
                    'code' => 'invalid-argument',
                    'message' => 'Record UUID must equal the course document ID.',
                ],
            ], 400);
        }

        $level = Level::where('record_uuid', $validated['level_id'])->first();
        if (!$level) {
            return response()->json([
                'error' => [
                    'code' => 'failed-precondition',
                    'message' => 'The level does not exist or is invalid.',
                ],
            ], 400);
        }

        $academicSession = AcademicSession::where('record_uuid', $validated['academic_session_id'])->first();
        if (!$academicSession) {
            return response()->json([
                'error' => [
                    'code' => 'failed-precondition',
                    'message' => 'The academic session does not exist or is invalid.',
                ],
            ], 400);
        }

        $result = DB::transaction(function () use ($validated, $lecturer, $lecturerUser, $level, $academicSession) {
            $course = Course::where('record_uuid', $validated['course_id'])->first();
            $now = now();

            if (!$course) {
                if ($validated['source_revision'] !== 1) {
                    throw ValidationException::withMessages([
                        'source_revision' => 'Source revision must be 1 for create operations.',
                    ]);
                }

                $eventId = $this->computeEventId((string) $lecturerUser->id, 'courses', $validated['course_id'], 'create', 1);

                $existingEvent = ChangeFeedEventId::where('event_id', $eventId)->first();
                if ($existingEvent) {
                    return [
                        'course_id' => $validated['course_id'],
                        'record_uuid' => $validated['course_id'],
                        'created' => false,
                        'source_revision' => 1,
                        'idempotent_replay' => true,
                        'status' => 200,
                    ];
                }

                $counter = ChangeFeedCounter::firstOrCreate(
                    ['lecturer_id' => $lecturer->user_id, 'collection_name' => 'courses'],
                    ['next_sequence' => 0]
                );
                $nextSequence = $counter->increment('next_sequence');

                $courseData = [
                    'record_uuid' => $validated['course_id'],
                    'code' => strtoupper(trim($validated['code'])),
                    'title' => trim($validated['title']),
                    'level_id' => $level->id,
                    'academic_session_id' => $academicSession->id,
                    'semester' => $validated['semester'],
                    'is_active' => $validated['is_active'],
                    'lecturer_id' => $lecturerUser->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'schema_version' => 1,
                    'change_version' => 1,
                ];

                $course = Course::create($courseData);

                $this->emitChangeFeedEntry(
                    $lecturerUser->id,
                    'courses',
                    $course->id,
                    'create',
                    1,
                    $courseData,
                    $nextSequence,
                    $eventId,
                    false
                );

                return [
                    'course_id' => $validated['course_id'],
                    'record_uuid' => $validated['course_id'],
                    'created' => true,
                    'source_revision' => 1,
                    'idempotent_replay' => false,
                    'status' => 200,
                ];
            }

            if ($course->lecturer_id !== $lecturerUser->id) {
                throw new HttpException(403, json_encode([
                    'error' => [
                        'code' => 'permission-denied',
                        'message' => 'The lecturer must own the course.',
                    ],
                ]));
            }

            if (!is_int($course->change_version) || $course->change_version < 1) {
                throw ValidationException::withMessages([
                    'course_id' => 'The course schema requires administrator review.',
                ]);
            }

            if ($validated['source_revision'] === $course->change_version) {
                return [
                    'course_id' => $validated['course_id'],
                    'record_uuid' => $validated['course_id'],
                    'created' => false,
                    'source_revision' => $validated['source_revision'],
                    'idempotent_replay' => true,
                    'status' => 200,
                ];
            }

            $eventId = $this->computeEventId((string) $lecturerUser->id, 'courses', $validated['course_id'], 'update', $validated['source_revision']);

            $existingEvent = ChangeFeedEventId::where('event_id', $eventId)->first();
            if ($existingEvent) {
                return [
                    'course_id' => $validated['course_id'],
                    'record_uuid' => $validated['course_id'],
                    'created' => false,
                    'source_revision' => $validated['source_revision'],
                    'idempotent_replay' => true,
                    'status' => 200,
                ];
            }

            $expectedRevision = $course->change_version + 1;
            if ($validated['source_revision'] !== $expectedRevision) {
                throw ValidationException::withMessages([
                    'source_revision' => "Source revision must be {$expectedRevision}.",
                ]);
            }

            $counter = ChangeFeedCounter::firstOrCreate(
                ['lecturer_id' => $lecturer->user_id, 'collection_name' => 'courses'],
                ['next_sequence' => 0]
            );
            $nextSequence = $counter->increment('next_sequence');

            $courseData = [
                'record_uuid' => $validated['course_id'],
                'code' => strtoupper(trim($validated['code'])),
                'title' => trim($validated['title']),
                'level_id' => $level->id,
                'academic_session_id' => $academicSession->id,
                'semester' => $validated['semester'],
                'is_active' => $validated['is_active'],
                'lecturer_id' => $lecturerUser->id,
                'created_at' => $course->created_at,
                'updated_at' => $now,
                'schema_version' => 1,
                'change_version' => $validated['source_revision'],
            ];

            $course->update($courseData);

            $this->emitChangeFeedEntry(
                $lecturerUser->id,
                'courses',
                $course->id,
                'update',
                $validated['source_revision'],
                $courseData,
                $nextSequence,
                $eventId,
                false
            );

            return [
                'course_id' => $validated['course_id'],
                'record_uuid' => $validated['course_id'],
                'created' => false,
                'source_revision' => $validated['source_revision'],
                'idempotent_replay' => false,
                'status' => 200,
            ];
        });

        return response()->json([
            'course_id' => $result['course_id'],
            'record_uuid' => $result['record_uuid'],
            'created' => $result['created'],
            'source_revision' => $result['source_revision'],
            'idempotent_replay' => $result['idempotent_replay'],
        ], $result['status']);
    }

    public function upsertLectureSession(Request $request): JsonResponse
    {
        $lecturer = $this->getLecturer($request);
        $lecturerUser = $this->getLecturerUser($request);

        $validated = $request->validate([
            'record_uuid' => 'required|uuid',
            'course_id' => 'required|uuid',
            'session_number' => 'required|integer|min:1',
            'week_number' => 'required|integer|min:1',
            'lecture_date' => 'required|date',
            'from_time' => 'required|string|max:8',
            'to_time' => 'required|string|max:8',
            'duration_minutes' => 'required|integer|min:1',
            'status' => 'required|string|in:scheduled,active,completed',
            'source_revision' => 'required|integer|min:1',
        ]);

        $course = Course::where('record_uuid', $validated['course_id'])->first();
        if (!$course) {
            return response()->json([
                'error' => [
                    'code' => 'failed-precondition',
                    'message' => 'The course does not exist or is invalid.',
                ],
            ], 400);
        }

        if ($course->lecturer_id !== $lecturerUser->id) {
            return response()->json([
                'error' => [
                    'code' => 'permission-denied',
                    'message' => 'The lecturer must own the course.',
                ],
            ], 403);
        }

        $result = DB::transaction(function () use ($validated, $lecturer, $lecturerUser, $course) {
            $lectureSession = LectureSession::where('record_uuid', $validated['record_uuid'])->first();
            $now = now();

            if (!$lectureSession) {
                if ($validated['source_revision'] !== 1) {
                    throw ValidationException::withMessages([
                        'source_revision' => 'Source revision must be 1 for create operations.',
                    ]);
                }

                $eventId = $this->computeEventId((string) $lecturerUser->id, 'lecture_sessions', $validated['record_uuid'], 'create', 1);

                $existingEvent = ChangeFeedEventId::where('event_id', $eventId)->first();
                if ($existingEvent) {
                    return [
                        'record_uuid' => $validated['record_uuid'],
                        'created' => false,
                        'source_revision' => 1,
                        'idempotent_replay' => true,
                        'status' => 200,
                    ];
                }

                $counter = ChangeFeedCounter::firstOrCreate(
                    ['lecturer_id' => $lecturer->user_id, 'collection_name' => 'lecture_sessions'],
                    ['next_sequence' => 0]
                );
                $nextSequence = $counter->increment('next_sequence');

                $sessionData = [
                    'record_uuid' => $validated['record_uuid'],
                    'course_id' => $course->id,
                    'lecturer_id' => $lecturerUser->id,
                    'session_number' => $validated['session_number'],
                    'week_number' => $validated['week_number'],
                    'lecture_date' => $validated['lecture_date'],
                    'from_time' => $validated['from_time'],
                    'to_time' => $validated['to_time'],
                    'duration_minutes' => $validated['duration_minutes'],
                    'status' => $validated['status'],
                    'started_at' => $now,
                    'schema_version' => 1,
                    'change_version' => 1,
                ];

                $lectureSession = LectureSession::create($sessionData);

                $this->emitChangeFeedEntry(
                    $lecturerUser->id,
                    'lecture_sessions',
                    $lectureSession->id,
                    'create',
                    1,
                    $sessionData,
                    $nextSequence,
                    $eventId,
                    false
                );

                return [
                    'record_uuid' => $validated['record_uuid'],
                    'created' => true,
                    'source_revision' => 1,
                    'idempotent_replay' => false,
                    'status' => 200,
                ];
            }

            if ($lectureSession->lecturer_id !== $lecturerUser->id) {
                throw ValidationException::withMessages([
                    'record_uuid' => 'The lecturer must own this lecture session.',
                ]);
            }

            if ($validated['source_revision'] === $lectureSession->change_version) {
                return [
                    'record_uuid' => $validated['record_uuid'],
                    'created' => false,
                    'source_revision' => $validated['source_revision'],
                    'idempotent_replay' => true,
                    'status' => 200,
                ];
            }

            $eventId = $this->computeEventId((string) $lecturerUser->id, 'lecture_sessions', $validated['record_uuid'], 'update', $validated['source_revision']);

            $existingEvent = ChangeFeedEventId::where('event_id', $eventId)->first();
            if ($existingEvent) {
                return [
                    'record_uuid' => $validated['record_uuid'],
                    'created' => false,
                    'source_revision' => $validated['source_revision'],
                    'idempotent_replay' => true,
                    'status' => 200,
                ];
            }

            $expectedRevision = $lectureSession->change_version + 1;
            if ($validated['source_revision'] !== $expectedRevision) {
                throw ValidationException::withMessages([
                    'source_revision' => "Source revision must be {$expectedRevision}.",
                ]);
            }

            $counter = ChangeFeedCounter::firstOrCreate(
                ['lecturer_id' => $lecturer->user_id, 'collection_name' => 'lecture_sessions'],
                ['next_sequence' => 0]
            );
            $nextSequence = $counter->increment('next_sequence');

            $sessionData = [
                'record_uuid' => $validated['record_uuid'],
                'course_id' => $course->id,
                'lecturer_id' => $lecturerUser->id,
                'session_number' => $validated['session_number'],
                'week_number' => $validated['week_number'],
                'lecture_date' => $validated['lecture_date'],
                'from_time' => $validated['from_time'],
                'to_time' => $validated['to_time'],
                'duration_minutes' => $validated['duration_minutes'],
                'status' => $validated['status'],
                'started_at' => $lectureSession->started_at,
                'schema_version' => 1,
                'change_version' => $validated['source_revision'],
            ];

            $lectureSession->update($sessionData);

            $this->emitChangeFeedEntry(
                $lecturerUser->id,
                'lecture_sessions',
                $lectureSession->id,
                'update',
                $validated['source_revision'],
                $sessionData,
                $nextSequence,
                $eventId,
                false
            );

            return [
                'record_uuid' => $validated['record_uuid'],
                'created' => false,
                'source_revision' => $validated['source_revision'],
                'idempotent_replay' => false,
                'status' => 200,
            ];
        });

        return response()->json([
            'record_uuid' => $result['record_uuid'],
            'created' => $result['created'],
            'source_revision' => $result['source_revision'],
            'idempotent_replay' => $result['idempotent_replay'],
        ], $result['status']);
    }

    public function createAttendanceRecord(Request $request): JsonResponse
    {
        $lecturer = $this->getLecturer($request);
        $lecturerUser = $this->getLecturerUser($request);

        $validated = $request->validate([
            'record_uuid' => 'required|uuid',
            'lecture_session_id' => 'required|uuid',
            'student_id' => 'required|uuid',
            'status' => 'required|string|in:present,absent,late,excused',
            'scanned_at' => 'required|date',
            'source_revision' => 'required|integer|min:1',
        ]);

        $lectureSession = LectureSession::where('record_uuid', $validated['lecture_session_id'])->first();
        if (!$lectureSession) {
            return response()->json([
                'error' => [
                    'code' => 'failed-precondition',
                    'message' => 'The lecture session does not exist.',
                ],
            ], 400);
        }

        if ($lectureSession->lecturer_id !== $lecturerUser->id) {
            return response()->json([
                'error' => [
                    'code' => 'permission-denied',
                    'message' => 'The lecturer must own the lecture session.',
                ],
            ], 403);
        }

        $student = User::where('record_uuid', $validated['student_id'])->first();
        if (!$student || $student->role !== 'student') {
            return response()->json([
                'error' => [
                    'code' => 'failed-precondition',
                    'message' => 'The student does not exist or is invalid.',
                ],
            ], 400);
        }

        $result = DB::transaction(function () use ($validated, $lecturer, $lecturerUser, $lectureSession, $student) {
            $attendanceRecord = AttendanceRecord::where('record_uuid', $validated['record_uuid'])->first();
            $now = now();

            if (!$attendanceRecord) {
                if ($validated['source_revision'] !== 1) {
                    throw ValidationException::withMessages([
                        'source_revision' => 'Source revision must be 1 for create operations.',
                    ]);
                }

                $eventId = $this->computeEventId((string) $lecturerUser->id, 'attendance_records', $validated['record_uuid'], 'create', 1);

                $existingEvent = ChangeFeedEventId::where('event_id', $eventId)->first();
                if ($existingEvent) {
                    return [
                        'record_uuid' => $validated['record_uuid'],
                        'created' => false,
                        'source_revision' => 1,
                        'idempotent_replay' => true,
                        'status' => 200,
                    ];
                }

                $counter = ChangeFeedCounter::firstOrCreate(
                    ['lecturer_id' => $lecturer->user_id, 'collection_name' => 'attendance_records'],
                    ['next_sequence' => 0]
                );
                $nextSequence = $counter->increment('next_sequence');

                $recordData = [
                    'record_uuid' => $validated['record_uuid'],
                    'lecture_session_id' => $lectureSession->id,
                    'student_id' => $student->id,
                    'lecturer_id' => $lecturerUser->id,
                    'status' => $validated['status'],
                    'scanned_at' => $validated['scanned_at'],
                    'schema_version' => 1,
                    'change_version' => 1,
                ];

                $attendanceRecord = AttendanceRecord::create($recordData);

                $this->emitChangeFeedEntry(
                    $lecturerUser->id,
                    'attendance_records',
                    $attendanceRecord->id,
                    'create',
                    1,
                    $recordData,
                    $nextSequence,
                    $eventId,
                    false
                );

                return [
                    'record_uuid' => $validated['record_uuid'],
                    'created' => true,
                    'source_revision' => 1,
                    'idempotent_replay' => false,
                    'status' => 200,
                ];
            }

            if ($attendanceRecord->lecturer_id !== $lecturerUser->id) {
                throw ValidationException::withMessages([
                    'record_uuid' => 'The lecturer must own this attendance record.',
                ]);
            }

            if ($validated['source_revision'] === $attendanceRecord->change_version) {
                return [
                    'record_uuid' => $validated['record_uuid'],
                    'created' => false,
                    'source_revision' => $validated['source_revision'],
                    'idempotent_replay' => true,
                    'status' => 200,
                ];
            }

            $eventId = $this->computeEventId((string) $lecturerUser->id, 'attendance_records', $validated['record_uuid'], 'update', $validated['source_revision']);

            $existingEvent = ChangeFeedEventId::where('event_id', $eventId)->first();
            if ($existingEvent) {
                return [
                    'record_uuid' => $validated['record_uuid'],
                    'created' => false,
                    'source_revision' => $validated['source_revision'],
                    'idempotent_replay' => true,
                    'status' => 200,
                ];
            }

            $expectedRevision = $attendanceRecord->change_version + 1;
            if ($validated['source_revision'] !== $expectedRevision) {
                throw ValidationException::withMessages([
                    'source_revision' => "Source revision must be {$expectedRevision}.",
                ]);
            }

            $counter = ChangeFeedCounter::firstOrCreate(
                ['lecturer_id' => $lecturer->user_id, 'collection_name' => 'attendance_records'],
                ['next_sequence' => 0]
            );
            $nextSequence = $counter->increment('next_sequence');

            $recordData = [
                'record_uuid' => $validated['record_uuid'],
                'lecture_session_id' => $lectureSession->id,
                'student_id' => $student->id,
                'lecturer_id' => $lecturerUser->id,
                'status' => $validated['status'],
                'scanned_at' => $validated['scanned_at'],
                'schema_version' => 1,
                'change_version' => $validated['source_revision'],
            ];

            $attendanceRecord->update($recordData);

            $this->emitChangeFeedEntry(
                $lecturerUser->id,
                'attendance_records',
                $attendanceRecord->id,
                'update',
                $validated['source_revision'],
                $recordData,
                $nextSequence,
                $eventId,
                false
            );

            return [
                'record_uuid' => $validated['record_uuid'],
                'created' => false,
                'source_revision' => $validated['source_revision'],
                'idempotent_replay' => false,
                'status' => 200,
            ];
        });

        return response()->json([
            'record_uuid' => $result['record_uuid'],
            'created' => $result['created'],
            'source_revision' => $result['source_revision'],
            'idempotent_replay' => $result['idempotent_replay'],
        ], $result['status']);
    }

    public function deleteCourse(Request $request): JsonResponse
    {
        $lecturer = $this->getLecturer($request);
        $lecturerUser = $this->getLecturerUser($request);

        $validated = $request->validate([
            'course_id' => 'required|uuid',
        ]);

        $result = DB::transaction(function () use ($validated, $lecturer, $lecturerUser) {
            $course = Course::where('record_uuid', $validated['course_id'])->first();
            if (!$course) {
                return [
                    'course_id' => $validated['course_id'],
                    'deleted' => false,
                    'idempotent_replay' => false,
                    'status' => 404,
                ];
            }

            if ($course->lecturer_id !== $lecturerUser->id) {
                throw new HttpException(403, json_encode([
                    'error' => [
                        'code' => 'permission-denied',
                        'message' => 'The lecturer must own the course.',
                    ],
                ]));
            }

            $eventId = $this->computeEventId((string) $lecturerUser->id, 'courses', $validated['course_id'], 'delete', 1);

            $existingEvent = ChangeFeedEventId::where('event_id', $eventId)->first();
            if ($existingEvent) {
                return [
                    'course_id' => $validated['course_id'],
                    'deleted' => false,
                    'idempotent_replay' => true,
                    'status' => 200,
                ];
            }

            $counter = ChangeFeedCounter::firstOrCreate(
                ['lecturer_id' => $lecturer->user_id, 'collection_name' => 'courses'],
                ['next_sequence' => 0]
            );
            $nextSequence = $counter->increment('next_sequence');

            $courseData = [
                'record_uuid' => $course->record_uuid,
                'code' => $course->code,
                'title' => $course->title,
                'level_id' => $course->level_id,
                'academic_session_id' => $course->academic_session_id,
                'semester' => $course->semester,
                'is_active' => $course->is_active,
                'lecturer_id' => $course->lecturer_id,
                'schema_version' => $course->schema_version,
                'change_version' => $course->change_version,
            ];

            $course->delete();

            $this->emitChangeFeedEntry(
                $lecturerUser->id,
                'courses',
                $course->id,
                'delete',
                1,
                [],
                $nextSequence,
                $eventId,
                false
            );

            return [
                'course_id' => $validated['course_id'],
                'deleted' => true,
                'idempotent_replay' => false,
                'status' => 200,
            ];
        });

        return response()->json([
            'course_id' => $result['course_id'],
            'deleted' => $result['deleted'],
            'idempotent_replay' => $result['idempotent_replay'],
        ], $result['status']);
    }

    public function deleteLectureSession(Request $request): JsonResponse
    {
        $lecturer = $this->getLecturer($request);
        $lecturerUser = $this->getLecturerUser($request);

        $validated = $request->validate([
            'record_uuid' => 'required|uuid',
        ]);

        $result = DB::transaction(function () use ($validated, $lecturer, $lecturerUser) {
            $lectureSession = LectureSession::where('record_uuid', $validated['record_uuid'])->first();
            if (!$lectureSession) {
                return [
                    'record_uuid' => $validated['record_uuid'],
                    'deleted' => false,
                    'idempotent_replay' => false,
                    'status' => 404,
                ];
            }

            if ($lectureSession->lecturer_id !== $lecturerUser->id) {
                throw ValidationException::withMessages([
                    'record_uuid' => 'The lecturer must own this lecture session.',
                ]);
            }

            $eventId = $this->computeEventId((string) $lecturerUser->id, 'lecture_sessions', $validated['record_uuid'], 'delete', 1);

            $existingEvent = ChangeFeedEventId::where('event_id', $eventId)->first();
            if ($existingEvent) {
                return [
                    'record_uuid' => $validated['record_uuid'],
                    'deleted' => false,
                    'idempotent_replay' => true,
                    'status' => 200,
                ];
            }

            $counter = ChangeFeedCounter::firstOrCreate(
                ['lecturer_id' => $lecturer->user_id, 'collection_name' => 'lecture_sessions'],
                ['next_sequence' => 0]
            );
            $nextSequence = $counter->increment('next_sequence');

            $sessionData = [
                'record_uuid' => $lectureSession->record_uuid,
                'course_id' => $lectureSession->course_id,
                'lecturer_id' => $lectureSession->lecturer_id,
                'session_number' => $lectureSession->session_number,
                'week_number' => $lectureSession->week_number,
                'lecture_date' => $lectureSession->lecture_date,
                'from_time' => $lectureSession->from_time,
                'to_time' => $lectureSession->to_time,
                'duration_minutes' => $lectureSession->duration_minutes,
                'status' => $lectureSession->status,
                'schema_version' => $lectureSession->schema_version,
                'change_version' => $lectureSession->change_version,
            ];

            $lectureSession->delete();

            $this->emitChangeFeedEntry(
                $lecturerUser->id,
                'lecture_sessions',
                $lectureSession->id,
                'delete',
                1,
                null,
                $nextSequence,
                $eventId,
                false
            );

            return [
                'record_uuid' => $validated['record_uuid'],
                'deleted' => true,
                'idempotent_replay' => false,
                'status' => 200,
            ];
        });

        return response()->json([
            'record_uuid' => $result['record_uuid'],
            'deleted' => $result['deleted'],
            'idempotent_replay' => $result['idempotent_replay'],
        ], $result['status']);
    }

    public function deleteAttendanceRecord(Request $request): JsonResponse
    {
        $lecturer = $this->getLecturer($request);
        $lecturerUser = $this->getLecturerUser($request);

        $validated = $request->validate([
            'record_uuid' => 'required|uuid',
        ]);

        $result = DB::transaction(function () use ($validated, $lecturer, $lecturerUser) {
            $attendanceRecord = AttendanceRecord::where('record_uuid', $validated['record_uuid'])->first();
            if (!$attendanceRecord) {
                return [
                    'record_uuid' => $validated['record_uuid'],
                    'deleted' => false,
                    'idempotent_replay' => false,
                    'status' => 404,
                ];
            }

            if ($attendanceRecord->lecturer_id !== $lecturerUser->id) {
                throw ValidationException::withMessages([
                    'record_uuid' => 'The lecturer must own this attendance record.',
                ]);
            }

            $eventId = $this->computeEventId((string) $lecturerUser->id, 'attendance_records', $validated['record_uuid'], 'delete', 1);

            $existingEvent = ChangeFeedEventId::where('event_id', $eventId)->first();
            if ($existingEvent) {
                return [
                    'record_uuid' => $validated['record_uuid'],
                    'deleted' => false,
                    'idempotent_replay' => true,
                    'status' => 200,
                ];
            }

            $counter = ChangeFeedCounter::firstOrCreate(
                ['lecturer_id' => $lecturer->user_id, 'collection_name' => 'attendance_records'],
                ['next_sequence' => 0]
            );
            $nextSequence = $counter->increment('next_sequence');

            $recordData = [
                'record_uuid' => $attendanceRecord->record_uuid,
                'lecture_session_id' => $attendanceRecord->lecture_session_id,
                'student_id' => $attendanceRecord->student_id,
                'lecturer_id' => $attendanceRecord->lecturer_id,
                'status' => $attendanceRecord->status,
                'scanned_at' => $attendanceRecord->scanned_at,
                'schema_version' => $attendanceRecord->schema_version,
                'change_version' => $attendanceRecord->change_version,
            ];

            $attendanceRecord->delete();

            $this->emitChangeFeedEntry(
                $lecturerUser->id,
                'attendance_records',
                $attendanceRecord->id,
                'delete',
                1,
                null,
                $nextSequence,
                $eventId,
                false
            );

            return [
                'record_uuid' => $validated['record_uuid'],
                'deleted' => true,
                'idempotent_replay' => false,
                'status' => 200,
            ];
        });

        return response()->json([
            'record_uuid' => $result['record_uuid'],
            'deleted' => $result['deleted'],
            'idempotent_replay' => $result['idempotent_replay'],
        ], $result['status']);
    }

    public function enrolStudent(Request $request): JsonResponse
    {
        $lecturer = $this->getLecturer($request);
        $lecturerUser = $this->getLecturerUser($request);

        $validated = $request->validate([
            'record_uuid' => 'required|uuid',
            'course_id' => 'required|uuid',
            'student_id' => 'required|uuid',
            'source_revision' => 'required|integer|min:1',
        ]);

        $course = Course::where('record_uuid', $validated['course_id'])->first();
        if (!$course) {
            return response()->json([
                'error' => [
                    'code' => 'failed-precondition',
                    'message' => 'The course does not exist.',
                ],
            ], 400);
        }

        if ($course->lecturer_id !== $lecturerUser->id) {
            return response()->json([
                'error' => [
                    'code' => 'permission-denied',
                    'message' => 'The lecturer must own the course.',
                ],
            ], 403);
        }

        $student = User::where('record_uuid', $validated['student_id'])->first();
        if (!$student || $student->role !== 'student') {
            return response()->json([
                'error' => [
                    'code' => 'failed-precondition',
                    'message' => 'The student does not exist or is invalid.',
                ],
            ], 400);
        }

        $result = DB::transaction(function () use ($validated, $lecturer, $lecturerUser, $course, $student) {
            $enrolment = Enrolment::where('record_uuid', $validated['record_uuid'])->first();
            $now = now();

            if (!$enrolment) {
                if ($validated['source_revision'] !== 1) {
                    throw ValidationException::withMessages([
                        'source_revision' => 'Source revision must be 1 for create operations.',
                    ]);
                }

                $eventId = $this->computeEventId((string) $lecturerUser->id, 'enrolments', $validated['record_uuid'], 'create', 1);

                $existingEvent = ChangeFeedEventId::where('event_id', $eventId)->first();
                if ($existingEvent) {
                    return [
                        'record_uuid' => $validated['record_uuid'],
                        'created' => false,
                        'source_revision' => 1,
                        'idempotent_replay' => true,
                        'status' => 200,
                    ];
                }

                $counter = ChangeFeedCounter::firstOrCreate(
                    ['lecturer_id' => $lecturer->user_id, 'collection_name' => 'enrolments'],
                    ['next_sequence' => 0]
                );
                $nextSequence = $counter->increment('next_sequence');

                $enrolmentData = [
                    'record_uuid' => $validated['record_uuid'],
                    'course_id' => $course->id,
                    'student_id' => $student->id,
                    'lecturer_id' => $lecturerUser->id,
                    'enrolled_at' => $now,
                    'schema_version' => 1,
                    'change_version' => 1,
                ];

                $enrolment = Enrolment::create($enrolmentData);

                $this->emitChangeFeedEntry(
                    $lecturerUser->id,
                    'enrolments',
                    $enrolment->id,
                    'create',
                    1,
                    $enrolmentData,
                    $nextSequence,
                    $eventId,
                    false
                );

                return [
                    'record_uuid' => $validated['record_uuid'],
                    'created' => true,
                    'source_revision' => 1,
                    'idempotent_replay' => false,
                    'status' => 200,
                ];
            }

            if ($enrolment->lecturer_id !== $lecturerUser->id) {
                throw ValidationException::withMessages([
                    'record_uuid' => 'The lecturer must own this enrolment.',
                ]);
            }

            if ($validated['source_revision'] === $enrolment->change_version) {
                return [
                    'record_uuid' => $validated['record_uuid'],
                    'created' => false,
                    'source_revision' => $validated['source_revision'],
                    'idempotent_replay' => true,
                    'status' => 200,
                ];
            }

            $eventId = $this->computeEventId((string) $lecturerUser->id, 'enrolments', $validated['record_uuid'], 'update', $validated['source_revision']);

            $existingEvent = ChangeFeedEventId::where('event_id', $eventId)->first();
            if ($existingEvent) {
                return [
                    'record_uuid' => $validated['record_uuid'],
                    'created' => false,
                    'source_revision' => $validated['source_revision'],
                    'idempotent_replay' => true,
                    'status' => 200,
                ];
            }

            $expectedRevision = $enrolment->change_version + 1;
            if ($validated['source_revision'] !== $expectedRevision) {
                throw ValidationException::withMessages([
                    'source_revision' => "Source revision must be {$expectedRevision}.",
                ]);
            }

            $counter = ChangeFeedCounter::firstOrCreate(
                ['lecturer_id' => $lecturer->user_id, 'collection_name' => 'enrolments'],
                ['next_sequence' => 0]
            );
            $nextSequence = $counter->increment('next_sequence');

            $enrolmentData = [
                'record_uuid' => $validated['record_uuid'],
                'course_id' => $course->id,
                'student_id' => $student->id,
                'lecturer_id' => $lecturerUser->id,
                'enrolled_at' => $enrolment->enrolled_at,
                'schema_version' => 1,
                'change_version' => $validated['source_revision'],
            ];

            $enrolment->update($enrolmentData);

            $this->emitChangeFeedEntry(
                $lecturerUser->id,
                'enrolments',
                $enrolment->id,
                'update',
                $validated['source_revision'],
                $enrolmentData,
                $nextSequence,
                $eventId,
                false
            );

            return [
                'record_uuid' => $validated['record_uuid'],
                'created' => false,
                'source_revision' => $validated['source_revision'],
                'idempotent_replay' => false,
                'status' => 200,
            ];
        });

        return response()->json([
            'record_uuid' => $result['record_uuid'],
            'created' => $result['created'],
            'source_revision' => $result['source_revision'],
            'idempotent_replay' => $result['idempotent_replay'],
        ], $result['status']);
    }

    public function removeEnrolment(Request $request): JsonResponse
    {
        $lecturer = $this->getLecturer($request);
        $lecturerUser = $this->getLecturerUser($request);

        $validated = $request->validate([
            'record_uuid' => 'required|uuid',
        ]);

        $result = DB::transaction(function () use ($validated, $lecturer, $lecturerUser) {
            $enrolment = Enrolment::where('record_uuid', $validated['record_uuid'])->first();
            if (!$enrolment) {
                return [
                    'record_uuid' => $validated['record_uuid'],
                    'removed' => false,
                    'idempotent_replay' => false,
                    'status' => 404,
                ];
            }

            if ($enrolment->lecturer_id !== $lecturerUser->id) {
                throw ValidationException::withMessages([
                    'record_uuid' => 'The lecturer must own this enrolment.',
                ]);
            }

            $eventId = $this->computeEventId((string) $lecturerUser->id, 'enrolments', $validated['record_uuid'], 'delete', 1);

            $existingEvent = ChangeFeedEventId::where('event_id', $eventId)->first();
            if ($existingEvent) {
                return [
                    'record_uuid' => $validated['record_uuid'],
                    'removed' => false,
                    'idempotent_replay' => true,
                    'status' => 200,
                ];
            }

            $counter = ChangeFeedCounter::firstOrCreate(
                ['lecturer_id' => $lecturer->user_id, 'collection_name' => 'enrolments'],
                ['next_sequence' => 0]
            );
            $nextSequence = $counter->increment('next_sequence');

            $enrolmentData = [
                'record_uuid' => $enrolment->record_uuid,
                'course_id' => $enrolment->course_id,
                'student_id' => $enrolment->student_id,
                'lecturer_id' => $enrolment->lecturer_id,
                'enrolled_at' => $enrolment->enrolled_at,
                'schema_version' => $enrolment->schema_version,
                'change_version' => $enrolment->change_version,
            ];

            $enrolment->delete();

            $this->emitChangeFeedEntry(
                $lecturerUser->id,
                'enrolments',
                $enrolment->id,
                'delete',
                1,
                null,
                $nextSequence,
                $eventId,
                false
            );

            return [
                'record_uuid' => $validated['record_uuid'],
                'removed' => true,
                'idempotent_replay' => false,
                'status' => 200,
            ];
        });

        return response()->json([
            'record_uuid' => $result['record_uuid'],
            'removed' => $result['removed'],
            'idempotent_replay' => $result['idempotent_replay'],
        ], $result['status']);
    }

    public function registerDevice(Request $request): JsonResponse
    {
        $lecturer = $this->getLecturer($request);
        $lecturerUser = $this->getLecturerUser($request);

        $validated = $request->validate([
            'device_id' => 'required|uuid',
            'device_info' => 'nullable|string|max:500',
        ]);

        $device = SyncDevice::updateOrCreate(
            ['device_id' => $validated['device_id'], 'lecturer_id' => $lecturerUser->id],
            [
                'device_info' => $validated['device_info'],
                'schema_version' => 1,
                'last_seen_at' => now(),
            ]
        );

        return response()->json([
            'device_id' => $device->device_id,
            'registered' => true,
        ]);
    }

    public function advanceCheckpoint(Request $request): JsonResponse
    {
        $lecturer = $this->getLecturer($request);
        $lecturerUser = $this->getLecturerUser($request);

        $validated = $request->validate([
            'device_id' => 'required|uuid',
            'collection_name' => 'required|string',
            'cursor' => 'required|uuid',
            'last_page_id' => 'nullable|uuid',
        ]);

        $device = SyncDevice::where('device_id', $validated['device_id'])
            ->where('lecturer_id', $lecturerUser->id)
            ->first();

        if (!$device) {
            return response()->json([
                'error' => [
                    'code' => 'not-found',
                    'message' => 'Device not found.',
                ],
            ], 404);
        }

        SyncCheckpoint::updateOrCreate(
            ['device_id' => $device->id, 'collection_name' => $validated['collection_name']],
            [
                'cursor' => $validated['cursor'],
                'last_page_id' => $validated['last_page_id'],
                'schema_version' => 1,
            ]
        );

        return response()->json([
            'device_id' => $validated['device_id'],
            'collection_name' => $validated['collection_name'],
            'advanced' => true,
        ]);
    }

    public function getChangeFeed(Request $request): JsonResponse
    {
        $lecturer = $this->getLecturer($request);
        $lecturerUser = $this->getLecturerUser($request);

        $validated = $request->validate([
            'collection_name' => 'nullable|string',
            'after_sequence' => 'nullable|integer|min:0',
            'limit' => 'nullable|integer|min:1|max:1000',
        ]);

        $query = ChangeFeed::where('lecturer_id', $lecturerUser->id);

        if ($validated['collection_name'] ?? null) {
            $query->where('collection_name', $validated['collection_name']);
        }

        if (isset($validated['after_sequence'])) {
            $query->where('sequence', '>', $validated['after_sequence']);
        }

        $query->orderBy('sequence', 'asc');

        $limit = $validated['limit'] ?? 100;
        $entries = $query->limit($limit)->get();

        return response()->json([
            'entries' => $entries->map(function ($entry) {
                return [
                    'change_id' => $entry->change_id,
                    'collection_name' => $entry->collection_name,
                    'document_id' => $entry->document_id,
                    'operation' => $entry->operation,
                    'data' => $entry->data,
                    'server_timestamp' => $entry->server_timestamp,
                    'sequence' => $entry->sequence,
                    'source_revision' => $entry->source_revision,
                    'event_id' => $entry->event_id,
                ];
            }),
            'count' => $entries->count(),
        ]);
    }

    public function advanceChangeFeedCheckpoint(Request $request): JsonResponse
    {
        $lecturer = $this->getLecturer($request);
        $lecturerUser = $this->getLecturerUser($request);

        $validated = $request->validate([
            'collection_name' => 'required|string',
            'sequence' => 'required|integer|min:0',
        ]);

        $changeFeedDevice = SyncDevice::firstOrCreate(
            ['device_id' => 'change_feed', 'lecturer_id' => $lecturerUser->id],
            [
                'device_info' => 'Change Feed Checkpoint',
                'schema_version' => 1,
            ]
        );

        SyncCheckpoint::updateOrCreate(
            ['device_id' => $changeFeedDevice->id, 'collection_name' => $validated['collection_name']],
            [
                'cursor' => null,
                'last_page_id' => null,
                'schema_version' => 1,
            ]
        );

        return response()->json([
            'collection_name' => $validated['collection_name'],
            'sequence' => $validated['sequence'],
            'advanced' => true,
        ]);
    }

    protected function computeEventId(string $lecturerUid, string $collectionName, string $documentId, string $operation, int $sourceRevision): string
    {
        $payload = "{$lecturerUid}:{$collectionName}:{$documentId}:{$operation}:{$sourceRevision}";
        return hash('sha256', $payload);
    }

    protected function emitChangeFeedEntry(
        int $lecturerId,
        string $collectionName,
        int $documentId,
        string $operation,
        int $sourceRevision,
        ?array $data,
        int $sequence,
        string $eventId,
        bool $eventIdAlreadyExists = false
    ): void {
        $changeId = Str::uuid()->toString();
        $now = now();

        ChangeFeed::create([
            'change_id' => $changeId,
            'lecturer_id' => $lecturerId,
            'collection_name' => $collectionName,
            'document_id' => $documentId,
            'operation' => $operation,
            'data' => $operation === 'delete' ? null : $data,
            'server_timestamp' => $now,
            'sequence' => $sequence,
            'source_revision' => $sourceRevision,
            'event_id' => $eventId,
            'schema_version' => 1,
        ]);

        if (!$eventIdAlreadyExists) {
            ChangeFeedEventId::create([
                'event_id' => $eventId,
                'lecturer_id' => $lecturerId,
                'collection_name' => $collectionName,
                'document_id' => $documentId,
                'operation' => $operation,
                'source_revision' => $sourceRevision,
                'change_id' => $changeId,
            ]);
        }
    }
}
