@extends('layouts.admin')

@section('page-header')
    <x-page-header
        title="Edit Level"
        subtitle="Update level information"
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Academic', 'url' => null],
            ['label' => 'Levels', 'url' => route('admin.levels.index')],
            ['label' => $level->name, 'url' => null],
        ]"
    />
@endsection

@section('page-content')
    <x-card>
        <form method="POST" action="{{ route('admin.levels.update', $level) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-sm font-bold">1</span>
                    Level Information
                </h3>

                <x-input label="Level Name" name="name" type="text" required :value="$level->name" :error="$errors->first('name')" help="Enter the academic level name" />
            </div>

            <div class="flex flex-col-reverse sm:flex-row gap-4 justify-end pt-4 border-t border-gray-200">
                <a href="{{ route('admin.levels.index') }}" class="w-full sm:w-auto">
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