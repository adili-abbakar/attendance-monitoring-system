<!-- Admin Mobile Drawer -->
<div id="adminMobileDrawer" class="hidden fixed inset-0 z-30 md:hidden" x-data="{ open: false }" @toggle-admin-drawer.window="open = !open">
    <div class="fixed inset-0 bg-black/50" @click="open = false"></div>
    <nav class="fixed left-0 top-0 h-full w-64 bg-white shadow-lg overflow-y-auto pt-16" :class="{ 'translate-x-0': open, '-translate-x-full': !open }" x-transition>
        <div class="p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-teal-50 text-teal-700' : 'text-gray-700 rounded-lg hover:bg-gray-100' }} rounded-lg font-medium"
                @click="open = false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4v4"></path>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('admin.lecturers.index') }}"
                class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.lecturers*') ? 'bg-teal-50 text-teal-700' : 'text-gray-700 rounded-lg hover:bg-gray-100' }} rounded-lg font-medium"
                @click="open = false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                    </path>
                </svg>
                Lecturers
            </a>

            <!-- Academic Dropdown in Mobile -->
            <div>
                <button type="button"
                    class="flex items-center gap-3 w-full px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium"
                    @click="academicOpen = !academicOpen">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    Academic
                    <svg class="w-4 h-4 ml-auto transition-transform" :class="{ 'rotate-180': academicOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="mt-1 space-y-1 ml-8" x-show="academicOpen" x-transition>
                    <a href="{{ route('admin.levels.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm {{ request()->routeIs('admin.levels*') ? 'text-teal-700 bg-teal-50' : 'text-gray-600 hover:bg-gray-50' }} rounded-lg transition-colors"
                        @click="open = false">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Levels
                    </a>
                    <a href="{{ route('admin.academic-sessions.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm {{ request()->routeIs('admin.academic-sessions*') ? 'text-teal-700 bg-teal-50' : 'text-gray-600 hover:bg-gray-50' }} rounded-lg transition-colors"
                        @click="open = false">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Academic Sessions
                    </a>
                    <a href="{{ route('admin.courses.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 text-sm {{ request()->routeIs('admin.courses*') ? 'text-teal-700 bg-teal-50' : 'text-gray-600 hover:bg-gray-50' }} rounded-lg transition-colors"
                        @click="open = false">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Courses
                    </a>
                </div>
            </div>

            <a href="{{ route('students.index') }}"
                class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('students.index') ? 'bg-teal-50 text-teal-700' : 'text-gray-700 rounded-lg hover:bg-gray-100' }} rounded-lg font-medium"
                @click="open = false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                </svg>
                Students
            </a>

            <hr class="my-4">
            <div class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-green-600 rounded-full flex items-center justify-center text-white font-medium">
                    {{ strtoupper(auth()->user()->name[0] ?? 'A') }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="post" class="mt-2">
                @csrf
                <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </nav>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminMobileDrawer', () => ({
            open: false,
            academicOpen: false,
        }));
    });
</script>