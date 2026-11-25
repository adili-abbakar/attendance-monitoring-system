@extends('layouts.admin')

@section('page-header')
    <x-page-header
        title="Dashboard"
        subtitle="Overview of the attendance monitoring system"
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Dashboard', 'url' => null],
        ]"
    />
@endsection

@section('page-content')
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Lecturers</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_lecturers'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $stats['active_lecturers'] }} active &bull; {{ $stats['inactive_lecturers'] }} inactive
                    </p>
                </div>
                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Students</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_students'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Registered in system</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Courses</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_courses'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['active_courses'] }} active courses</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card class="hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Academic Sessions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_academic_sessions'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        @if ($stats['current_academic_session'])
                            Current: {{ $stats['current_academic_session']->name }}
                        @else
                            No sessions configured
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column (2/3) - Recent Activity -->
        <div class="lg:col-span-2">
            <x-card title="Recent System Activity" subtitle="Latest enrolments and attendance records">
                @if ($recentActivity->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Time</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Activity</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($recentActivity as $activity)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $activity['time']->diffForHumans() }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center
                                                    @if ($activity['color'] === 'blue') bg-blue-100 text-blue-600
                                                    @elseif ($activity['color'] === 'green') bg-green-100 text-green-600
                                                    @elseif ($activity['color'] === 'yellow') bg-yellow-100 text-yellow-600
                                                    @elseif ($activity['color'] === 'red') bg-red-100 text-red-600
                                                    @else bg-gray-100 text-gray-600
                                                    @endif">
                                                    @if ($activity['icon'] === 'user-plus')
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                                    @elseif ($activity['icon'] === 'check-circle')
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-900">{{ $activity['description'] }}</p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $activity['user'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <x-empty-state
                        icon='<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>'
                        title="No Recent Activity"
                        description="System activity will appear here as lecturers enrol students and record attendance."
                    />
                @endif
            </x-card>

            <!-- System Overview -->
            <x-card title="System Overview" subtitle="Quick access to management sections">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.lecturers.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-teal-300 hover:bg-teal-50 transition-colors">
                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        </div>
                        <h4 class="font-medium text-gray-900">Lecturers</h4>
                        <p class="text-sm text-gray-500 mt-1">{{ $stats['total_lecturers'] }} total ({{ $stats['active_lecturers'] }} active)</p>
                    </a>

                    <a href="{{ route('students.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path></svg>
                        </div>
                        <h4 class="font-medium text-gray-900">Students</h4>
                        <p class="text-sm text-gray-500 mt-1">{{ $stats['total_students'] }} registered</p>
                    </a>

                    <a href="{{ route('admin.courses.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h4 class="font-medium text-gray-900">Courses</h4>
                        <p class="text-sm text-gray-500 mt-1">{{ $stats['total_courses'] }} total ({{ $stats['active_courses'] }} active)</p>
                    </a>

                    <a href="{{ route('admin.academic-sessions.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h4 class="font-medium text-gray-900">Academic Sessions</h4>
                        <p class="text-sm text-gray-500 mt-1">{{ $stats['total_academic_sessions'] }} sessions</p>
                    </a>
                </div>
            </x-card>
        </div>

        <!-- Right Column (1/3) - Quick Actions & Info -->
        <div class="space-y-6">
            <x-card title="Quick Actions">
                <div class="space-y-3">
                    <x-button variant="primary" full-width class="justify-start gap-3" href="{{ route('admin.lecturers.create') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                        Add New Lecturer
                    </x-button>

                    <x-button variant="secondary" full-width class="justify-start gap-3" href="{{ route('admin.courses.create') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Create Course
                    </x-button>

                    <x-button variant="secondary" full-width class="justify-start gap-3" href="{{ route('admin.levels.create') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Add Level
                    </x-button>

                    <x-button variant="secondary" full-width class="justify-start gap-3" href="{{ route('admin.academic-sessions.create') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        New Academic Session
                    </x-button>
                </div>
            </x-card>

            <x-card title="System Information">
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Laravel Version</p>
                        <p class="text-sm text-gray-900">{{ app()->version() }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">PHP Version</p>
                        <p class="text-sm text-gray-900">{{ PHP_VERSION }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Database</p>
                        <p class="text-sm text-gray-900">SQLite ({{ config('database.connections.sqlite.database') }})</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Environment</p>
                        <p class="text-sm text-gray-900 capitalize">{{ config('app.env') }}</p>
                    </div>
                </div>
            </x-card>

            <x-card title="Levels" subtitle="{{ $stats['total_levels'] }} levels configured">
                @php
                    $levels = \App\Models\Level::orderBy('name')->get();
                @endphp
                @if ($levels->isNotEmpty())
                    <ul class="space-y-2">
                        @foreach ($levels as $level)
                            <li class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-900">{{ $level->name }}</span>
                                <span class="text-xs text-gray-500">{{ $level->courses_count ?? 0 }} courses</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500 text-center py-4">No levels configured</p>
                @endif
            </x-card>
        </div>
    </div>
@endsection