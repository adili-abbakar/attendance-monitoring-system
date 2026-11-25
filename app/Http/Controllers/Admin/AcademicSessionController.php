<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AcademicSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = AcademicSession::withCount('courses');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $sessions = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.academic-sessions.index', compact('sessions'));
    }

    public function create()
    {
        return view('admin.academic-sessions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:academic_sessions,name',
        ]);

        AcademicSession::create([
            'record_uuid' => Str::uuid(),
            'name' => $validated['name'],
        ]);

        return redirect()->route('admin.academic-sessions.index')
            ->with('success', 'Academic session created successfully.');
    }

    public function edit(AcademicSession $academicSession)
    {
        return view('admin.academic-sessions.edit', compact('academicSession'));
    }

    public function update(Request $request, AcademicSession $academicSession)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('academic_sessions', 'name')->ignore($academicSession->id)],
        ]);

        $academicSession->update($validated);

        return redirect()->route('admin.academic-sessions.index')
            ->with('success', 'Academic session updated successfully.');
    }

    public function destroy(AcademicSession $academicSession)
    {
        if ($academicSession->courses()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete academic session with assigned courses.',
            ], 422);
        }

        $academicSession->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Academic session deleted successfully.',
        ]);
    }
}