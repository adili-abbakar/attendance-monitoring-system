@extends('layouts.app')
@section('title', 'Student Management')

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
                <!-- Header with Action Buttons -->
                <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center mb-6">
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search by name, email, ID..."
                                class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex gap-3 w-full md:w-auto">
                        <button
                            class="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5" aria-hidden="true" role="img" viewBox="0 0 512 512">
                                    <path fill="currentColor"
                                        d=" M3.9 54.9C10.5 40.9 24.5 32 40 32h432c15.5 0 29.5 8.9 36.1 22.9s4.6 30.5-5.2
                                42.5L320 320.9V448c0 12.1-6.8 23.2-17.7 28.6s-23.8
                                4.3-33.5-3l-64-48c-8.1-6-12.8-15.5-12.8-25.6v-79.1L9 97.3C-.7 85.4-2.8 68.8 3.9 54.9">
                                </path>
                            </svg>
                            <span>Filter</span>
                        </button>
                        <button
                            class="flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                            <svg class="w-5 h-5 text-white" aria-hidden="true" role="img" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2h-6v6a1 1 0 1 1-2 0v-6H5a1 1 0 1 1 0-2h6V5a1 1 0 0 1 1-1">
                                </path>
                            </svg>
                            <span>Add Student</span>
                        </button>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
                    <button
                        class="px-4 py-2 bg-teal-100 text-teal-700 rounded-full whitespace-nowrap font-medium transition-colors">All
                        Students (245)</button>
                    <button
                        class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-full whitespace-nowrap hover:bg-gray-50 transition-colors">Active
                        (234)</button>
                    <button
                        class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-full whitespace-nowrap hover:bg-gray-50 transition-colors">Inactive
                        (11)</button>
                    <button
                        class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-full whitespace-nowrap hover:bg-gray-50 transition-colors">Pending
                        (5)</button>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total Students</p>
                                <p class="text-2xl font-bold text-gray-900">245</p>
                            </div>
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-xl">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">New This Month</p>
                                <p class="text-2xl font-bold text-gray-900">18</p>
                            </div>
                            <div
                                class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 text-xl">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Active Classes</p>
                                <p class="text-2xl font-bold text-gray-900">12</p>
                            </div>
                            <div
                                class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 text-xl">
                                <i class="fas fa-book"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Avg Attendance</p>
                                <p class="text-2xl font-bold text-gray-900">87.5%</p>
                            </div>
                            <div
                                class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center text-teal-600 text-xl">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 bg-gray-50">
                                    <th class="px-6 py-3 text-left">
                                        <input type="checkbox" class="rounded border-gray-300">
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Student Info</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">ID</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Department</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Enrolled Classes
                                    </th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Row 1 -->
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" class="rounded border-gray-300">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=john" alt="Student"
                                                class="w-10 h-10 rounded-full">
                                            <div>
                                                <p class="font-medium text-gray-900">John Doe</p>
                                                <p class="text-sm text-gray-500">john.doe@university.edu</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">STU-2024-001</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Computer Science</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">4</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Row 2 -->
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" class="rounded border-gray-300">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=sarah"
                                                alt="Student" class="w-10 h-10 rounded-full">
                                            <div>
                                                <p class="font-medium text-gray-900">Sarah Smith</p>
                                                <p class="text-sm text-gray-500">sarah.smith@university.edu</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">STU-2024-002</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Engineering</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">5</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button
                                                class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button
                                                class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Row 3 -->
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" class="rounded border-gray-300">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=michael"
                                                alt="Student" class="w-10 h-10 rounded-full">
                                            <div>
                                                <p class="font-medium text-gray-900">Michael Johnson</p>
                                                <p class="text-sm text-gray-500">michael.j@university.edu</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">STU-2024-003</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Business</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">3</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Pending</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button
                                                class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button
                                                class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Row 4 -->
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" class="rounded border-gray-300">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=emma" alt="Student"
                                                class="w-10 h-10 rounded-full">
                                            <div>
                                                <p class="font-medium text-gray-900">Emma Williams</p>
                                                <p class="text-sm text-gray-500">emma.w@university.edu</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">STU-2024-004</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Computer Science</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">4</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button
                                                class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button
                                                class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Row 5 -->
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" class="rounded border-gray-300">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=david"
                                                alt="Student" class="w-10 h-10 rounded-full">
                                            <div>
                                                <p class="font-medium text-gray-900">David Brown</p>
                                                <p class="text-sm text-gray-500">david.b@university.edu</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">STU-2024-005</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Engineering</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">5</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Inactive</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button
                                                class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button
                                                class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table Footer -->
                    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between bg-gray-50">
                        <p class="text-sm text-gray-600">Showing 5 of 245 students</p>
                        <div class="flex gap-2">
                            <button
                                class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition-colors">Previous</button>
                            <button
                                class="px-3 py-1 bg-teal-600 text-white rounded-lg text-sm hover:bg-teal-700 transition-colors">1</button>
                            <button
                                class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition-colors">2</button>
                            <button
                                class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition-colors">3</button>
                            <button
                                class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition-colors">Next</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection
