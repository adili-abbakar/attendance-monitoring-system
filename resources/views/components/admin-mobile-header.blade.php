<!-- Admin Mobile Header -->
<header class="md:hidden fixed top-0 left-0 right-0 h-16 bg-white border-b border-gray-200 flex items-center px-4 z-40">
    <button id="adminMobileMenuBtn" class="p-2 hover:bg-gray-100 rounded-lg" @click="$dispatch('toggle-admin-drawer')">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>
    <div class="flex-1 flex items-center justify-center">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-gradient-to-br from-teal-500 to-green-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <span class="font-bold text-gray-900">UniAttend</span>
        </div>
    </div>
    <div class="w-10"></div>
</header>