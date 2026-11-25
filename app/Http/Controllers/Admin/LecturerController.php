<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Lecturer;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LecturerController extends Controller
{
    public function index(Request $request)
    {
        $query = Lecturer::with(['user', 'courses']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('staff_number', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereHas('user', fn($q) => $q->where('status', true));
            } elseif ($request->status === 'inactive') {
                $query->whereHas('user', fn($q) => $q->where('status', false));
            }
        }

        $lecturers = $query->orderBy('staff_number')->paginate(15)->withQueryString();

        return view('admin.lecturers.index', compact('lecturers'));
    }

    public function create()
    {
        return view('admin.lecturers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'staff_number' => 'required|string|max:50|unique:lecturers,staff_number',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'status' => 'boolean',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'lecturer',
                'status' => $validated['status'] ?? true,
                'record_uuid' => Str::uuid(),
            ]);

            Lecturer::create([
                'user_id' => $user->id,
                'record_uuid' => Str::uuid(),
                'staff_number' => $validated['staff_number'],
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'address' => $validated['address'] ?? null,
                'schema_version' => 1,
                'change_version' => 1,
            ]);
        });

        return redirect()->route('admin.lecturers.index')
            ->with('success', 'Lecturer created successfully.');
    }

    public function show(Lecturer $lecturer)
    {
        $lecturer->load(['user', 'courses.level', 'courses.academicSession', 'lectureSessions', 'syncDevices']);

        return view('admin.lecturers.show', compact('lecturer'));
    }

    public function edit(Lecturer $lecturer)
    {
        $lecturer->load('user');
        return view('admin.lecturers.edit', compact('lecturer'));
    }

    public function update(Request $request, Lecturer $lecturer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($lecturer->user_id)],
            'staff_number' => ['required', 'string', 'max:50', Rule::unique('lecturers', 'staff_number')->ignore($lecturer->user_id)],
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'status' => 'boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($validated, $lecturer) {
            $lecturer->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'status' => $validated['status'] ?? true,
                'password' => $validated['password'] ? Hash::make($validated['password']) : $lecturer->user->password,
            ]);

            $lecturer->update([
                'staff_number' => $validated['staff_number'],
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);
        });

        return redirect()->route('admin.lecturers.index')
            ->with('success', 'Lecturer updated successfully.');
    }

    public function toggleStatus(Lecturer $lecturer)
    {
        $newStatus = !$lecturer->user->status;
        
        $lecturer->user->update(['status' => $newStatus]);

        $statusText = $newStatus ? 'activated' : 'deactivated';

        return response()->json([
            'status' => 'success',
            'message' => "Lecturer {$statusText} successfully.",
            'new_status' => $newStatus,
        ]);
    }

    public function destroy(Lecturer $lecturer)
    {
        // Check if lecturer has courses
        if ($lecturer->courses()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete lecturer with assigned courses. Reassign courses first.',
            ], 422);
        }

        $user = $lecturer->user;
        $lecturer->delete();
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Lecturer deleted successfully.',
        ]);
    }
}