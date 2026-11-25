@extends('layouts.admin')

@section('page-header')
    <x-page-header
        title="Add Academic Session"
        subtitle="Create a new academic year/session"
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Academic', 'url' => null],
            ['label' => 'Academic Sessions', 'url' => route('admin.academic-sessions.index')],
            ['label' => 'Add Session', 'url' => null],
        ]"
    />
@endsection

@section('page-content')
    <x-card>
        <form method="POST" action="{{ route('admin.academic-sessions.store') }}" class="space-y-6">
            @csrf

            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-sm font-bold">1</span>
                    Session Information
                </h3>

                <x-input label="Session Name" name="name" type="text" required placeholder="e.g., 2024/2025, Fall 2024, Spring 2025" :error="$errors->first('name')" help="Enter the academic session name (e.g., 2024/2025, Fall 2024)" />
            </div>

            <div class="flex flex-col-reverse sm:flex-row gap-4 justify-end pt-4 border-t border-gray-200">
                <a href="{{ route('admin.academic-sessions.index') }}" class="w-full sm:w-auto">
                    <x-button variant="secondary">Cancel</x-button>
                </a>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Create Session
                </x-button>
            </div>
        </form>
    </x-card>
@endsection