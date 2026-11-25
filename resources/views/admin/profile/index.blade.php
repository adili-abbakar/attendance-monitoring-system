@extends('layouts.admin')

@section('page-header')
    <x-page-header
        title="Profile"
        subtitle="Manage your administrator account settings"
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Profile', 'url' => route('admin.profile.index')],
        ]"
    />
@endsection

@section('page-content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Profile Info -->
        <div class="lg:col-span-2 space-y-6">
            <x-card title="Account Information">
                <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <x-input label="Full Name" name="name" type="text" required :value="$user->name" :error="$errors->first('name')" />
                        <x-input label="Email Address" name="email" type="email" required :value="$user->email" :error="$errors->first('email')" />
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h3>
                        <p class="text-sm text-gray-600 mb-4">Leave blank to keep current password.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-input label="New Password" name="password" type="password" placeholder="••••••••" :error="$errors->first('password')" help="Minimum 8 characters" />
                            <x-input label="Confirm New Password" name="password_confirmation" type="password" placeholder="••••••••" :error="$errors->first('password_confirmation')" />
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <x-button variant="primary" type="submit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Save Changes
                        </x-button>
                    </div>
                </form>
            </x-card>

            <x-card title="Account Details">
                <dl class="space-y-4">
                    <div class="grid grid-cols-3 gap-4">
                        <dt class="text-sm text-gray-500">User ID</dt>
                        <dd class="text-sm font-medium text-gray-900 col-span-2">{{ $user->id }}</dd>

                        <dt class="text-sm text-gray-500">Role</dt>
                        <dd class="text-sm font-medium text-gray-900 col-span-2">
                            <x-badge variant="primary" dot>{{ ucfirst($user->role) }}</x-badge>
                        </dd>

                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd class="text-sm font-medium text-gray-900 col-span-2">
                            <x-badge :variant="$user->status ? 'success' : 'danger'" dot>
                                {{ $user->status ? 'Active' : 'Inactive' }}
                            </x-badge>
                        </dd>

                        <dt class="text-sm text-gray-500">Created</dt>
                        <dd class="text-sm font-medium text-gray-900 col-span-2">{{ $user->created_at->format('F j, Y g:i A') }}</dd>

                        <dt class="text-sm text-gray-500">Last Updated</dt>
                        <dd class="text-sm font-medium text-gray-900 col-span-2">{{ $user->updated_at->format('F j, Y g:i A') }}</dd>
                    </div>
                </dl>
            </x-card>
        </div>

        <!-- Right Column - Quick Actions -->
        <div class="space-y-6">
            <x-card title="Quick Actions" padding="p-4">
                <div class="space-y-3">
                    <a href="{{ route('admin.dashboard') }}" class="block w-full">
                        <x-button variant="secondary" full-width class="justify-start gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4v4"></path></svg>
                            Back to Dashboard
                        </x-button>
                    </a>

                    <a href="{{ route('admin.lecturers.index') }}" class="block w-full">
                        <x-button variant="secondary" full-width class="justify-start gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Manage Lecturers
                        </x-button>
                    </a>

                    <a href="{{ route('admin.courses.index') }}" class="block w-full">
                        <x-button variant="secondary" full-width class="justify-start gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Manage Courses
                        </x-button>
                    </a>
                </div>
            </x-card>

            <x-card title="System Information">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm text-gray-500">Laravel Version</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ app()->version() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">PHP Version</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ PHP_VERSION }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Environment</dt>
                        <dd class="text-sm font-medium text-gray-900 capitalize">{{ config('app.env') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Database</dt>
                        <dd class="text-sm font-medium text-gray-900">SQLite</dd>
                    </div>
                </dl>
            </x-card>
        </div>
    </div>
@endsection