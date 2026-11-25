@extends('layouts.admin')

@section('page-header')
    <x-page-header
        title="Courses"
        subtitle="Manage courses and assignments"
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Academic', 'url' => null],
            ['label' => 'Courses', 'url' => route('admin.courses.index')],
        ]"
        :actions="
            <x-button variant='primary' href='{{ route('admin.courses.create') }}'>
                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'></path></svg>
                Add Course
            </x-button>
        "
    />
@endsection

@section('page-content')
    <x-card class="mb-6" padding="p-4">
        <form method="GET" action="{{ route('admin.courses.index') }}" class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by code or title..."
                        class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <select name="level_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Levels</option>
                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                            {{ $level->name }}
                        </option>
                    @endforeach
                </select>

                <select name="academic_session_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Sessions</option>
                    @foreach ($academicSessions as $session)
                        <option value="{{ $session->id }}" {{ request('academic_session_id') == $session->id ? 'selected' : '' }}>
                            {{ $session->name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                @if (request()->hasAny(['search', 'level_id', 'academic_session_id', 'status']))
                    <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors self-end">
                        Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </x-card>

    <x-card>
        @if ($courses->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Level</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Academic Session</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Semester</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lecturer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($courses as $course)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $course->code }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $course->title }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $course->level->name ?? 'N/A' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $course->academicSession->name ?? 'N/A' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">Sem {{ $course->semester }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $course->lecturer->user->name ?? 'Unassigned' }}</td>
                                <td class="px-4 py-4">
                                    <x-badge :variant="$course->is_active ? 'success' : 'danger'" :dot="true">
                                        {{ $course->is_active ? 'Active' : 'Inactive' }}
                                    </x-badge>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.courses.edit', $course) }}"
                                            class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <x-confirm-dialog
                                            id="delete-course-{{ $course->id }}"
                                            title="Delete Course"
                                            message="This will permanently delete the course. This action cannot be undone."
                                            confirmText="Delete"
                                            variant="danger"
                                        />

                                        <button type="button"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Delete"
                                            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'delete-course-{{ $course->id }}' } }))">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v12m-6 0h12m-6 0h.01M8 7h8m-6 0V7a2 2 0 012-2h6a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2v-10a2 2 0 012-2z"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-4 border-t border-gray-200">
                <x-table.pagination :paginator="$courses" />
            </div>
        @else
            <x-empty-state
                icon='<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>'
                title="No Courses Found"
                description="No courses match your current search criteria."
                :action="
                    <x-button variant='primary' href='{{ route('admin.courses.create') }}'>
                        <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'></path></svg>
                        Create First Course
                    </x-button>
                "
            />
        @endif
    </x-card>
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