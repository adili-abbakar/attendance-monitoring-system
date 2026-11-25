<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $query = Level::withCount('courses');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $levels = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.levels.index', compact('levels'));
    }

    public function create()
    {
        return view('admin.levels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:levels,name',
        ]);

        Level::create([
            'record_uuid' => Str::uuid(),
            'name' => $validated['name'],
        ]);

        return redirect()->route('admin.levels.index')
            ->with('success', 'Level created successfully.');
    }

    public function edit(Level $level)
    {
        return view('admin.levels.edit', compact('level'));
    }

    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('levels', 'name')->ignore($level->id)],
        ]);

        $level->update($validated);

        return redirect()->route('admin.levels.index')
            ->with('success', 'Level updated successfully.');
    }

    public function destroy(Level $level)
    {
        if ($level->courses()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete level with assigned courses.',
            ], 422);
        }

        $level->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Level deleted successfully.',
        ]);
    }
}