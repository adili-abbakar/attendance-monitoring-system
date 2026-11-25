@extends('layouts.admin')

@section('page-header')
    <x-page-header
        title="Add Level"
        subtitle="Create a new academic level"
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Academic', 'url' => null],
            ['label' => 'Levels', 'url' => route('admin.levels.index')],
            ['label' => 'Add Level', 'url' => null],
        ]"
    />
@endsection

@section('page-content')
    <x-card>
        <form method="POST" action="{{ route('admin.levels.store') }}" class="space-y-6">
            @csrf

            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-sm font-bold">1</span>
                    Level Information
                </h3>

                <x-input label="Level Name" name="name" type="text" required placeholder="e.g., Level 100, Year 1, Freshman" :error="$errors->first('name')" help="Enter the academic level name (e.g., Level 100, Year 1, Freshman)" />
            </div>

            <div class="flex flex-col-reverse sm:flex-row gap-4 justify-end pt-4 border-t border-gray-200">
                <a href="{{ route('admin.levels.index') }}" class="w-full sm:w-auto">
                    <x-button variant="secondary">Cancel</x-button>
                </a>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Create Level
                </x-button>
            </div>
        </form>
    </x-card>
@endsection