@extends('layouts.admin')

@section('page-header')
    <x-page-header
        title="Lecturers"
        subtitle="Manage lecturer accounts and profiles"
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Lecturers', 'url' => route('admin.lecturers.index')],
        ]"
        :actions="
            <x-button variant='primary' href='{{ route('admin.lecturers.create') }}'>
                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'></path></svg>
                Add Lecturer
            </x-button>
        "
    />
@endsection

@section('page-content')
    <!-- Search and Filters -->
    <x-card class="mb-6" padding="p-4">
        <form method="GET" action="{{ route('admin.lecturers.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, staff number..."
                        class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="flex gap-3 sm:w-auto">
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                @if (request()->has('search') || request()->has('status'))
                    <a href="{{ route('admin.lecturers.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </x-card>

    <!-- Lecturers Table -->
    <x-card>
        @if ($lecturers->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lecturer</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Staff Number</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Phone</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Courses</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($lecturers as $lecturer)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-green-600 rounded-full flex items-center justify-center text-white font-medium">
                                            {{ strtoupper($lecturer->user->name[0] ?? 'L') }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $lecturer->user->name }}</p>
                                            <p class="text-xs text-gray-500">ID: {{ $lecturer->user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900 font-mono">{{ $lecturer->staff_number }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $lecturer->user->email }}</td>
                                <td class="px-4 py-4 text-sm text-gray-500">{{ $lecturer->phone ?? '—' }}</td>
                                <td class="px-4 py-4">
                                    <x-badge :variant="$lecturer->user->status ? 'success' : 'danger'" :dot="true">
                                        {{ $lecturer->user->status ? 'Active' : 'Inactive' }}
                                    </x-badge>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $lecturer->courses_count ?? 0 }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.lecturers.show', $lecturer) }}"
                                            class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                        <a href="{{ route('admin.lecturers.edit', $lecturer) }}"
                                            class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <form action="{{ route('admin.lecturers.toggle-status', $lecturer) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="p-2 {{ $lecturer->user->status ? 'text-yellow-600 hover:bg-yellow-50' : 'text-green-600 hover:bg-green-50' }} rounded-lg transition-colors"
                                                title="{{ $lecturer->user->status ? 'Deactivate' : 'Activate' }}"
                                                onclick="return confirm('Are you sure you want to {{ $lecturer->user->status ? 'deactivate' : 'activate' }} this lecturer?')">
                                                @if ($lecturer->user->status)
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                                @endif
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
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Delete"
                                            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'delete-lecturer-{{ $lecturer->id }}' } }))">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v12m-6 0h12m-6 0h.01M8 7h8m-6 0V7a2 2 0 012-2h6a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2v-10a2 2 0 012-2z"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-4 border-t border-gray-200">
                <x-table.pagination :paginator="$lecturers" />
            </div>
        @else
            <x-empty-state
                icon='<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>'
                title="No Lecturers Found"
                description="No lecturers match your current search criteria."
                :action="
                    <x-button variant='primary' href='{{ route('admin.lecturers.create') }}'>
                        <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'></path></svg>
                        Add First Lecturer
                    </x-button>
                "
            />
        @endif
    </x-card>
@endsection

@push('scripts')
<script>
    // Handle modal opening
    document.addEventListener('open-modal', (e) => {
        const modal = document.getElementById(e.detail.id);
        if (modal) {
            modal.classList.remove('hidden');
        }
    });

    // Handle confirmed delete
    document.addEventListener('confirmed-delete-lecturer-{{ $lecturer->id }}', () => {
        // The form submission is handled by the confirm-dialog component
    });
</script>
@endpush