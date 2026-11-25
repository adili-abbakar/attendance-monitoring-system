@extends('layouts.admin')

@section('page-header')
    <x-page-header
        title="{{ $lecturer->user->name }}"
        subtitle="Staff Number: {{ $lecturer->staff_number }}"
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Lecturers', 'url' => route('admin.lecturers.index')],
            ['label' => $lecturer->user->name, 'url' => null],
        ]"
        :actions="
            <div class='flex gap-2'>
                <a href='{{ route('admin.lecturers.edit', $lecturer) }}'>
                    <x-button variant='secondary'>
                        <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'></path></svg>
                        Edit
                    </x-button>
                </a>
                <form action='{{ route('admin.lecturers.toggle-status', $lecturer) }}' method='POST' class='inline'>
                    @csrf
                    @method('PATCH')
                    <button type='submit'
                        class='inline-flex items-center gap-2 px-4 py-2 {{ $lecturer->user->status ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} rounded-lg font-medium transition-colors'
                        onclick="return confirm('Are you sure you want to {{ $lecturer->user->status ? 'deactivate' : 'activate' }} this lecturer?')">
                        @if ($lecturer->user->status)
                            <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'></path></svg>
                            Deactivate
                        @else
                            <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'></path></svg>
                            Activate
                        @endif
                    </button>
                </form>
            </div>
        "
    />
@endsection

@section('page-content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Profile Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Card -->
            <x-card>
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 bg-gradient-to-br from-teal-500 to-green-600 rounded-2xl flex items-center justify-center text-white text-3xl font-bold">
                        {{ strtoupper($lecturer->user->name[0] ?? 'L') }}
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $lecturer->user->name }}</h3>
                        <p class="text-gray-600 mt-1">{{ $lecturer->user->email }}</p>
                        <div class="flex flex-wrap gap-4 mt-4 text-sm text-gray-500">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Staff: {{ $lecturer->staff_number }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                User ID: {{ $lecturer->user->id }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Role: Lecturer
                            </span>
                            <span class="flex items-center gap-1">
                                <x-badge :variant="$lecturer->user->status ? 'success' : 'danger'" :dot="true">
                                    {{ $lecturer->user->status ? 'Active' : 'Inactive' }}
                                </x-badge>
                            </span>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Contact & Personal Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-card title="Contact Information">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm text-gray-500">Email</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $lecturer->user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Phone</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $lecturer->phone ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Address</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $lecturer->address ?? 'Not provided' }}</dd>
                        </div>
                    </dl>
                </x-card>

                <x-card title="Personal Information">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm text-gray-500">Gender</dt>
                            <dd class="text-sm font-medium text-gray-900 capitalize">{{ $lecturer->gender ?? 'Not specified' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Date of Birth</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $lecturer->date_of_birth?->format('F j, Y') ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Account Created</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $lecturer->user->created_at->format('F j, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Last Updated</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $lecturer->user->updated_at->format('F j, Y') }}</dd>
                        </div>
                    </dl>
                </x-card>
            </div>

            <!-- Courses -->
            <x-card title="Assigned Courses ({{ $lecturer->courses_count ?? 0 }})">
                @if ($lecturer->courses->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Level</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Academic Session</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Semester</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($lecturer->courses as $course)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $course->code }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $course->title }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $course->level->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $course->academicSession->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">Semester {{ $course->semester }}</td>
                                        <td class="px-4 py-3">
                                            <x-badge :variant="$course->is_active ? 'success' : 'danger'" :dot="true">
                                                {{ $course->is_active ? 'Active' : 'Inactive' }}
                                            </x-badge>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <x-empty-state
                        icon='<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>'
                        title="No Courses Assigned"
                        description="This lecturer has not been assigned to any courses yet."
                    />
                @endif
            </x-card>

            <!-- Sync Devices -->
            <x-card title="Sync Devices ({{ $lecturer->syncDevices->count() }})">
                @if ($lecturer->syncDevices->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Device ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Platform</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">App Version</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Last Sync</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($lecturer->syncDevices as $device)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-sm font-mono text-gray-900">{{ Str::limit($device->device_id, 20) }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $device->platform ?? 'Unknown' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $device->app_version ?? 'Unknown' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $device->last_sync_at?->diffForHumans() ?? 'Never' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500 text-center py-4">No sync devices registered.</p>
                @endif
            </x-card>
        </div>

        <!-- Right Column - Quick Stats -->
        <div class="space-y-6">
            <x-card title="Statistics">
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Total Courses</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $lecturer->courses_count ?? 0 }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Active Courses</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $lecturer->courses->where('is_active', true)->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Lecture Sessions</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $lecturer->lectureSessions->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Sync Devices</dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $lecturer->syncDevices->count() }}</dd>
                    </div>
                </dl>
            </x-card>

            <x-card title="Account Actions" padding="p-4">
                <div class="space-y-3">
                    <a href="{{ route('admin.lecturers.edit', $lecturer) }}" class="block w-full">
                        <x-button variant="secondary" full-width class="justify-start gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit Profile
                        </x-button>
                    </a>

                    <form action="{{ route('admin.lecturers.toggle-status', $lecturer) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="w-full"
                            onclick="return confirm('Are you sure you want to {{ $lecturer->user->status ? 'deactivate' : 'activate' }} this lecturer?')">
                            <x-button variant="{{ $lecturer->user->status ? 'warning' : 'success' }}" full-width class="justify-start gap-3">
                                @if ($lecturer->user->status)
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Deactivate Account
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    Activate Account
                                @endif
                            </x-button>
                        </button>
                    </form>

                    <x-confirm-dialog
                        id="delete-lecturer-{{ $lecturer->id }}"
                        title="Delete Lecturer"
                        message="This will permanently delete the lecturer account and all associated data. This action cannot be undone."
                        confirmText="Delete"
                        variant="danger"
                    />

                    <button type="button"
                        class="w-full"
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'delete-lecturer-{{ $lecturer->id }}' } }))">
                        <x-button variant="danger" full-width class="justify-start gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v12m-6 0h12m-6 0h.01M8 7h8m-6 0V7a2 2 0 012-2h6a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2v-10a2 2 0 012-2z"></path></svg>
                            Delete Lecturer
                        </x-button>
                    </button>
                </div>
            </x-card>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('open-modal', (e) => {
        const modal = document.getElementById(e.detail.id);
        if (modal) {
            modal.classList.remove('hidden');
        }
    });
</script>
@endpush