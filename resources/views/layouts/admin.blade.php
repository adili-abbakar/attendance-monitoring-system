@extends('layouts.base')

@section('content')
    <body class="bg-gray-50">
        @if (session('success') || session('error') || session('warning') || session('info'))
            <x-flash-messages />
        @endif

        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <x-admin-sidebar />

            <!-- Mobile Header -->
            <x-admin-mobile-header />

            <!-- Mobile Drawer -->
            <x-admin-mobile-drawer />

            <!-- Main Content -->
            <main class="flex-1 overflow-auto md:mt-0 mt-16">
                <div class="p-4 md:p-8 max-w-7xl mx-auto">
                    @yield('page-header')
                    @yield('page-content')
                </div>
            </main>
        </div>

        @stack('scripts')
    </body>
@endsection