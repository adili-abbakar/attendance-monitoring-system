<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Level;
use App\Models\AcademicSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['level', 'academicSession', 'lecturer.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        if ($request->filled('academic_session_id')) {
            $query->where('academic_session_id', $request->academic_session_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $courses = $query->orderBy('code')->paginate(15)->withQueryString();
        $levels = Level::orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name')->get();

        return view('admin.courses.index', compact('courses', 'levels', 'academicSessions'));
    }

    public function create()
    {
        $levels = Level::orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name')->get();
        $lecturers = User::where('role', 'lecturer')
            ->where('status', true)
            ->whereHas('lecturer')
            ->with('lecturer')
            ->orderBy('name')
            ->get();

        return view('admin.courses.create', compact('levels', 'academicSessions', 'lecturers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:courses,code',
            'title' => 'required|string|max:255',
            'level_id' => 'required|exists:levels,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'semester' => 'required|integer|min:1|max:12',
            'lecturer_id' => 'required|exists:users,id',
            'is_active' => 'boolean',
        ]);

        DB::transaction(function () use ($validated) {
            Course::create([
                'record_uuid' => Str::uuid(),
                'code' => strtoupper($validated['code']),
                'title' => $validated['title'],
                'level_id' => $validated['level_id'],
                'academic_session_id' => $validated['academic_session_id'],
                'semester' => $validated['semester'],
                'lecturer_id' => $validated['lecturer_id'],
                'is_active' => $validated['is_active'] ?? true,
                'schema_version' => 1,
                'change_version' => 1,
            ]);
        });

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        $course->load(['level', 'academicSession', 'lecturer.user']);
        $levels = Level::orderBy('name')->get();
        $academicSessions = AcademicSession::orderBy('name')->get();
        $lecturers = User::where('role', 'lecturer')
            ->where('status', true)
            ->whereHas('lecturer')
            ->with('lecturer')
            ->orderBy('name')
            ->get();

        return view('admin.courses.edit', compact('course', 'levels', 'academicSessions', 'lecturers'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('courses', 'code')->ignore($course->id)],
            'title' => 'required|string|max:255',
            'level_id' => 'required|exists:levels,id',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'semester' => 'required|integer|min:1|max:12',
            'lecturer_id' => 'required|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $course->update([
            'code' => strtoupper($validated['code']),
            'title' => $validated['title'],
            'level_id' => $validated['level_id'],
            'academic_session_id' => $validated['academic_session_id'],
            'semester' => $validated['semester'],
            'lecturer_id' => $validated['lecturer_id'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        // Check for related records
        if ($course->lectureSessions()->exists() || $course->enrolments()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete course with lecture sessions or enrolments.',
            ], 422);
        }

        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Course deleted successfully.',
        ]);
    }
}