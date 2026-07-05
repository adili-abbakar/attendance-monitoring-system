@extends('layouts.app')


@section('title', 'Dashboard')
@section('page-content')
    <!-- Main Container -->
    <div class="flex h-screen overflow-hidden">
        <x-sidebar />
        <x-mobile-header />
        <x-mobile-drawer />
        <!-- Main Content -->
        <main class="flex-1 overflow-auto md:mt-0 mt-16">
            <div class="p-4 md:p-8 max-w-7xl mx-auto">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-gray-600 mt-2">Welcome back, Dr. Sarah Anderson</p>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <!-- Total Students -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Total Students</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">285</p>
                                <p class="text-xs text-green-600 mt-2">+12 this semester</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Classes Today -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Classes Today</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">4</p>
                                <p class="text-xs text-gray-600 mt-2">2 completed</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Avg Attendance -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Avg Attendance</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">87.5%</p>
                                <p class="text-xs text-green-600 mt-2">+2.3% from last week</p>
                            </div>
                            <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Actions -->
                    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium">Pending Actions</p>
                                <p class="text-3xl font-bold text-gray-900 mt-2">5</p>
                                <p class="text-xs text-orange-600 mt-2">3 urgent</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column (2/3) -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Recent Activity -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="p-6 border-b border-gray-200">
                                <h2 class="text-lg font-bold text-gray-900">Recent Activity</h2>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Time</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Action</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Class</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-900">10:45 AM</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">Started Attendance</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">CS-101</td>
                                            <td class="px-6 py-4 text-sm"><span
                                                    class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">Active</span>
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-900">09:30 AM</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">Ended Session</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">CS-103</td>
                                            <td class="px-6 py-4 text-sm"><span
                                                    class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">Completed</span>
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-900">08:15 AM</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">Student Added</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">CS-102</td>
                                            <td class="px-6 py-4 text-sm"><span
                                                    class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">Updated</span>
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-900">Yesterday</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">Report Generated</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">CS-101</td>
                                            <td class="px-6 py-4 text-sm"><span
                                                    class="px-2 py-1 bg-purple-100 text-purple-700 rounded text-xs font-medium">Exported</span>
                                            </td>
                                        </tr>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-900">Yesterday</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">Attendance Corrected</td>
                                            <td class="px-6 py-4 text-sm text-gray-600">CS-103</td>
                                            <td class="px-6 py-4 text-sm"><span
                                                    class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium">Edited</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Attendance Trend -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-gray-900 mb-6">Attendance Trend</h2>
                            <div class="h-64 flex items-end justify-between gap-2">
                                <div class="flex-1 flex flex-col items-center">
                                    <div class="w-full bg-gray-200 rounded" style="height: 150px;"></div>
                                    <p class="text-xs text-gray-600 mt-2">Mon</p>
                                </div>
                                <div class="flex-1 flex flex-col items-center">
                                    <div class="w-full bg-teal-400 rounded" style="height: 180px;"></div>
                                    <p class="text-xs text-gray-600 mt-2">Tue</p>
                                </div>
                                <div class="flex-1 flex flex-col items-center">
                                    <div class="w-full bg-teal-500 rounded" style="height: 220px;"></div>
                                    <p class="text-xs text-gray-600 mt-2">Wed</p>
                                </div>
                                <div class="flex-1 flex flex-col items-center">
                                    <div class="w-full bg-teal-400 rounded" style="height: 190px;"></div>
                                    <p class="text-xs text-gray-600 mt-2">Thu</p>
                                </div>
                                <div class="flex-1 flex flex-col items-center">
                                    <div class="w-full bg-green-500 rounded" style="height: 210px;"></div>
                                    <p class="text-xs text-gray-600 mt-2">Fri</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column (1/3) -->
                    <div class="space-y-6">
                        <!-- Upcoming Sessions -->
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <div class="p-6 border-b border-gray-200">
                                <h2 class="text-lg font-bold text-gray-900">Upcoming Sessions</h2>
                            </div>
                            <div class="divide-y divide-gray-200">
                                <div class="p-6 hover:bg-gray-50 transition-colors cursor-pointer">
                                    <p class="font-semibold text-gray-900">CS-101: Algorithms</p>
                                    <p class="text-sm text-gray-600 mt-1">Today at 2:00 PM</p>
                                    <p class="text-xs text-gray-500 mt-2">Room: A-301</p>
                                </div>
                                <div class="p-6 hover:bg-gray-50 transition-colors cursor-pointer">
                                    <p class="font-semibold text-gray-900">CS-102: Database</p>
                                    <p class="text-sm text-gray-600 mt-1">Today at 4:30 PM</p>
                                    <p class="text-xs text-gray-500 mt-2">Room: B-102</p>
                                </div>
                                <div class="p-6 hover:bg-gray-50 transition-colors cursor-pointer">
                                    <p class="font-semibold text-gray-900">CS-103: Web Dev</p>
                                    <p class="text-sm text-gray-600 mt-1">Tomorrow at 10:00 AM</p>
                                    <p class="text-xs text-gray-500 mt-2">Room: A-205</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-linear-to-br from-teal-500 to-green-600 rounded-lg p-6 text-white">
                            <h3 class="font-bold text-lg mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="qr-scanner.html"
                                    class="block w-full bg-white/20 hover:bg-white/30 text-white font-medium py-2 px-4 rounded-lg text-center transition-colors">
                                    Start Attendance
                                </a>
                                <a href="attendance.html"
                                    class="block w-full bg-white/20 hover:bg-white/30 text-white font-medium py-2 px-4 rounded-lg text-center transition-colors">
                                    Manual Attendance
                                </a>
                                <a href="reports.html"
                                    class="block w-full bg-white/20 hover:bg-white/30 text-white font-medium py-2 px-4 rounded-lg text-center transition-colors">
                                    View Reports
                                </a>
                            </div>
                        </div>

                        <!-- Statistics Card -->
                        <div class="bg-white rounded-lg border border-gray-200 p-6">
                            <h3 class="font-bold text-gray-900 mb-4">This Week Stats</h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <p class="text-sm font-medium text-gray-600">Attendance Rate</p>
                                        <p class="text-sm font-bold text-gray-900">87.5%</p>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: 87.5%;"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <p class="text-sm font-medium text-gray-600">Classes Completed</p>
                                        <p class="text-sm font-bold text-gray-900">12/15</p>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: 80%;"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <p class="text-sm font-medium text-gray-600">Reports Generated</p>
                                        <p class="text-sm font-bold text-gray-900">5/8</p>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-teal-500 h-2 rounded-full" style="width: 62.5%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileDrawer = document.getElementById('mobileDrawer');
        const drawerBackdrop = document.getElementById('drawerBackdrop');

        mobileMenuBtn?.addEventListener('click', () => {
            mobileDrawer.classList.toggle('hidden');
        });

        drawerBackdrop?.addEventListener('click', () => {
            mobileDrawer.classList.add('hidden');
        });

        // Close drawer when link is clicked
        document.querySelectorAll('#mobileDrawer a').forEach(link => {
            link.addEventListener('click', () => {
                mobileDrawer.classList.add('hidden');
            });
        });
    </script>

@endsection
