<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lecturer;
use App\Models\Student;
use App\Models\Course;
use App\Models\Level;
use App\Models\AcademicSession;
use App\Models\Enrolment;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Lecturer statistics
        $totalLecturers = Lecturer::count();
        $activeLecturers = User::where('role', 'lecturer')->where('status', true)->count();
        $inactiveLecturers = User::where('role', 'lecturer')->where('status', false)->count();

        // Student statistics
        $totalStudents = Student::count();

        // Course statistics
        $totalCourses = Course::count();
        $activeCourses = Course::where('is_active', true)->count();

        // Academic sessions
        $totalAcademicSessions = AcademicSession::count();
        $currentAcademicSession = AcademicSession::latest()->first();

        // Levels
        $totalLevels = Level::count();

        // Recent activity (enrolments and attendance records)
        $recentEnrolments = Enrolment::with(['student.user', 'course', 'lecturer.user'])
            ->latest()
            ->limit(5)
            ->get();

        $recentAttendance = AttendanceRecord::with(['student.user', 'lectureSession.course', 'lecturer.user'])
            ->latest('scanned_at')
            ->limit(5)
            ->get();

        // Combine and sort recent activity
        $recentActivity = collect();
        
        foreach ($recentEnrolments as $enrolment) {
            $recentActivity->push([
                'type' => 'enrolment',
                'icon' => 'user-plus',
                'color' => 'blue',
                'description' => "{$enrolment->student->user->name} enrolled in {$enrolment->course->code}",
                'time' => $enrolment->created_at,
                'user' => $enrolment->lecturer->user->name ?? 'System',
            ]);
        }

        foreach ($recentAttendance as $attendance) {
            $recentActivity->push([
                'type' => 'attendance',
                'icon' => 'check-circle',
                'color' => 'green',
                'description' => "{$attendance->student->user->name} marked {$attendance->status} in {$attendance->lectureSession->course->code}",
                'time' => $attendance->scanned_at,
                'user' => $attendance->lecturer->user->name ?? 'System',
            ]);
        }

        $recentActivity = $recentActivity->sortByDesc('time')->take(10)->values();

        // Quick stats for cards
        $stats = [
            'total_lecturers' => $totalLecturers,
            'active_lecturers' => $activeLecturers,
            'inactive_lecturers' => $inactiveLecturers,
            'total_students' => $totalStudents,
            'total_courses' => $totalCourses,
            'active_courses' => $activeCourses,
            'total_academic_sessions' => $totalAcademicSessions,
            'current_academic_session' => $currentAcademicSession,
            'total_levels' => $totalLevels,
        ];

        return view('admin.dashboard.index', compact('stats', 'recentActivity'));
    }
}