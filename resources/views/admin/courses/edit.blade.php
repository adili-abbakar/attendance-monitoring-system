@extends('layouts.admin')

@section('page-header')
    <x-page-header
        title="Edit Course"
        subtitle="Update course information and assignment"
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Academic', 'url' => null],
            ['label' => 'Courses', 'url' => route('admin.courses.index')],
            ['label' => $course->code, 'url' => null],
        ]"
    />
@endsection

@section('page-content')
    <x-card>
        <form method="POST" action="{{ route('admin.courses.update', $course) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-sm font-bold">1</span>
                    Course Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input label="Course Code" name="code" type="text" required :value="$course->code" :error="$errors->first('code')" help="Unique course code" />
                    <x-input label="Course Title" name="title" type="text" required :value="$course->title" :error="$errors->first('title')" />
                    <x-select label="Level" name="level_id" :options="$levels->pluck('name', 'id')->toArray()" required :value="$course->level_id" placeholder="Select level" :error="$errors->first('level_id')" />
                    <x-select label="Academic Session" name="academic_session_id" :options="$academicSessions->pluck('name', 'id')->toArray()" required :value="$course->academic_session_id" placeholder="Select session" :error="$errors->first('academic_session_id')" />
                    <x-input label="Semester" name="semester" type="number" required min="1" max="12" :value="$course->semester" :error="$errors->first('semester')" />
                    <x-select label="Lecturer" name="lecturer_id" :options="$lecturers->pluck('name', 'id')->toArray()" required :value="$course->lecturer_id" placeholder="Select lecturer" :error="$errors->first('lecturer_id')" />
                </div>
            </div>

            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold">2</span>
                    Status
                </h3>

                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $course->is_active ? 'checked' : '' }} class="w-4 h-4 text-teal-600 rounded focus:ring-teal-500">
                        <span class="text-sm text-gray-700">Active (visible to lecturers and students)</span>
                    </label>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row gap-4 justify-end pt-4 border-t border-gray-200">
                <a href="{{ route('admin.courses.index') }}" class="w-full sm:w-auto">
                    <x-button variant="secondary">Cancel</x-button>
                </a>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save Changes
                </x-button>
            </div>
        </form>
    </x-card>
@endsection