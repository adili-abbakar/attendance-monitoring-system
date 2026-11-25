@extends('layouts.admin')

@section('page-header')
    <x-page-header
        title="Levels"
        subtitle="Manage academic levels and year groups"
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Academic', 'url' => null],
            ['label' => 'Levels', 'url' => route('admin.levels.index')],
        ]"
        :actions="
            <x-button variant='primary' href='{{ route('admin.levels.create') }}'>
                <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'></path></svg>
                Add Level
            </x-button>
        "
    />
@endsection

@section('page-content')
    <!-- Search -->
    <x-card class="mb-6" padding="p-4">
        <form method="GET" action="{{ route('admin.levels.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search levels..."
                        class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            @if (request()->has('search'))
                <a href="{{ route('admin.levels.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors self-end">
                    Clear Search
                </a>
            @endif
        </form>
    </x-card>

    <!-- Levels Table -->
    <x-card>
        @if ($levels->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Level Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Courses</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($levels as $level)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4">
                                    <p class="font-medium text-gray-900">{{ $level->name }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $level->id }}</p>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $level->courses_count ?? 0 }} course(s)</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.levels.edit', $level) }}"
                                            class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <x-confirm-dialog
                                            id="delete-level-{{ $level->id }}"
                                            title="Delete Level"
                                            message="This will permanently delete the level. This action cannot be undone."
                                            confirmText="Delete"
                                            variant="danger"
                                        />

                                        <button type="button"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Delete"
                                            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'delete-level-{{ $level->id }}' } }))">
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
                <x-table.pagination :paginator="$levels" />
            </div>
        @else
            <x-empty-state
                icon='<svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>'
                title="No Levels Found"
                description="No academic levels have been configured yet."
                :action="
                    <x-button variant='primary' href='{{ route('admin.levels.create') }}'>
                        <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'></path></svg>
                        Create First Level
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