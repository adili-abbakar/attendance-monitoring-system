<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Add New Student - Attendance Management System</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                font-family:
                    -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,
                    "Helvetica Neue", Arial, sans-serif;
            }
            .sidebar {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .sidebar.hidden {
                transform: translateX(-100%);
            }
            @media (max-width: 768px) {
                .sidebar {
                    position: fixed;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100vh;
                    z-index: 40;
                }
            }
            .form-input {
                transition: all 0.2s ease;
            }
            .form-input:focus {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
            }
            .section-divider {
                position: relative;
                text-align: center;
                margin: 2rem 0;
            }
            .section-divider::before {
                content: "";
                position: absolute;
                left: 0;
                top: 50%;
                width: 100%;
                height: 1px;
                background: linear-gradient(
                    90deg,
                    transparent,
                    #e5e7eb,
                    transparent
                );
            }
            .section-divider span {
                position: relative;
                background: white;
                padding: 0 1rem;
                color: #6b7280;
                font-size: 0.875rem;
                font-weight: 500;
            }
            .success-icon {
                animation: scaleIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            @keyframes scaleIn {
                from {
                    transform: scale(0);
                }
                to {
                    transform: scale(1);
                }
            }
            .toast {
                animation: slideUp 0.3s ease;
            }
            @keyframes slideUp {
                from {
                    transform: translateY(100%);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        </style>
    </head>
    <body class="bg-gradient-to-br from-slate-50 to-slate-100">
        <!-- Mobile Header -->
        <header
            class="md:hidden sticky top-0 z-30 bg-white border-b border-slate-200"
        >
            <div class="flex items-center justify-between px-4 py-3">
                <button
                    id="mobileMenuBtn"
                    class="p-2 hover:bg-slate-100 rounded-lg transition"
                >
                    <svg
                        class="w-6 h-6 text-slate-700"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        ></path>
                    </svg>
                </button>
                <h1 class="text-lg font-bold text-slate-900">Add Student</h1>
                <div class="w-10"></div>
            </div>
        </header>

        <!-- Sidebar -->
        <aside
            id="sidebar"
            class="sidebar hidden md:flex md:static w-64 bg-white border-r border-slate-200 flex-col"
        >
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-teal-400 to-emerald-500 rounded-lg flex items-center justify-center"
                    >
                        <span class="text-white font-bold text-lg">U</span>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-900">University</h2>
                        <p class="text-xs text-slate-500">Admin Portal</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto p-4">
                <div class="space-y-2">
                    <a
                        href="admin-student-management.html"
                        class="flex items-center gap-3 px-4 py-3 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"
                            ></path>
                        </svg>
                        <span>Students</span>
                    </a>
                    <a
                        href="admin-lecturer-management.html"
                        class="flex items-center gap-3 px-4 py-3 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                            ></path>
                        </svg>
                        <span>Lecturers</span>
                    </a>
                    <a
                        href="admin-class-management.html"
                        class="flex items-center gap-3 px-4 py-3 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3"
                            ></path>
                        </svg>
                        <span>Classes</span>
                    </a>
                    <a
                        href="admin-attendance-reports.html"
                        class="flex items-center gap-3 px-4 py-3 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            ></path>
                        </svg>
                        <span>Reports</span>
                    </a>
                    <a
                        href="admin-bulk-upload.html"
                        class="flex items-center gap-3 px-4 py-3 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4v16m8-8H4"
                            ></path>
                        </svg>
                        <span>Bulk Upload</span>
                    </a>
                </div>

                <div class="border-t border-slate-200 my-4 pt-4">
                    <a
                        href="admin-add-student.html"
                        class="flex items-center gap-3 px-4 py-3 bg-emerald-50 text-emerald-600 rounded-lg transition font-medium"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                            ></path>
                        </svg>
                        <span>Add Student</span>
                    </a>
                    <a
                        href="admin-add-lecturer.html"
                        class="flex items-center gap-3 px-4 py-3 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition mt-2"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                            ></path>
                        </svg>
                        <span>Add Lecturer</span>
                    </a>
                    <a
                        href="admin-add-admin.html"
                        class="flex items-center gap-3 px-4 py-3 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition mt-2"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"
                            ></path>
                        </svg>
                        <span>Add Admin</span>
                    </a>
                </div>
            </nav>

            <div class="border-t border-slate-200 p-4">
                <div
                    class="flex items-center gap-3 px-4 py-3 bg-slate-50 rounded-lg"
                >
                    <img
                        src="https://via.placeholder.com/40/10b981/ffffff?text=AD"
                        alt="Admin"
                        class="w-10 h-10 rounded-full"
                    />
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-900 truncate">
                            Admin User
                        </p>
                        <p class="text-xs text-slate-500 truncate">
                            admin@university.edu
                        </p>
                    </div>
                </div>
                <button
                    class="w-full mt-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-lg transition"
                >
                    Logout
                </button>
            </div>
        </aside>

        <!-- Overlay for mobile -->
        <div
            id="sidebarOverlay"
            class="hidden md:hidden fixed inset-0 bg-black/50 z-30"
        ></div>

        <!-- Main Content -->
        <main class="flex-1">
            <div class="max-w-2xl mx-auto p-4 md:p-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1
                        class="text-3xl md:text-4xl font-bold text-slate-900 mb-2"
                    >
                        Add New Student
                    </h1>
                    <p class="text-slate-600">
                        Fill in the form below to register a new student in the
                        system
                    </p>
                </div>

                <!-- Form Card -->
                <div
                    id="formContainer"
                    class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden"
                >
                    <!-- Progress Bar -->
                    <div class="h-1 bg-slate-100">
                        <div
                            id="progressBar"
                            class="h-full bg-gradient-to-r from-emerald-400 to-teal-500 w-0 transition-all duration-300"
                        ></div>
                    </div>

                    <form id="studentForm" class="p-6 md:p-8">
                        <!-- Basic Information Section -->
                        <div>
                            <h2
                                class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2"
                            >
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full text-sm font-bold"
                                    >1</span
                                >
                                Basic Information
                            </h2>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8"
                            >
                                <div>
                                    <label
                                        for="firstName"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                        >First Name *</label
                                    >
                                    <input
                                        type="text"
                                        id="firstName"
                                        name="firstName"
                                        required
                                        class="form-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                        placeholder="John"
                                    />
                                    <span
                                        class="text-xs text-red-500 mt-1 hidden"
                                        id="firstNameError"
                                        >First name is required</span
                                    >
                                </div>
                                <div>
                                    <label
                                        for="lastName"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                        >Last Name *</label
                                    >
                                    <input
                                        type="text"
                                        id="lastName"
                                        name="lastName"
                                        required
                                        class="form-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                        placeholder="Doe"
                                    />
                                    <span
                                        class="text-xs text-red-500 mt-1 hidden"
                                        id="lastNameError"
                                        >Last name is required</span
                                    >
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8"
                            >
                                <div>
                                    <label
                                        for="studentId"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                        >Student ID *</label
                                    >
                                    <input
                                        type="text"
                                        id="studentId"
                                        name="studentId"
                                        required
                                        class="form-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                        placeholder="STU-2024-001"
                                    />
                                    <span
                                        class="text-xs text-red-500 mt-1 hidden"
                                        id="studentIdError"
                                        >Student ID is required</span
                                    >
                                </div>
                                <div>
                                    <label
                                        for="email"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                        >Email Address *</label
                                    >
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        required
                                        class="form-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                        placeholder="john.doe@university.edu"
                                    />
                                    <span
                                        class="text-xs text-red-500 mt-1 hidden"
                                        id="emailError"
                                        >Valid email is required</span
                                    >
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        for="phone"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                        >Phone Number</label
                                    >
                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        class="form-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                        placeholder="+1 (555) 123-4567"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="dateOfBirth"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                        >Date of Birth *</label
                                    >
                                    <input
                                        type="date"
                                        id="dateOfBirth"
                                        name="dateOfBirth"
                                        required
                                        class="form-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                    />
                                    <span
                                        class="text-xs text-red-500 mt-1 hidden"
                                        id="dateOfBirthError"
                                        >Date of birth is required</span
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Section Divider -->
                        <div class="section-divider">
                            <span>Academic Information</span>
                        </div>

                        <!-- Academic Section -->
                        <div>
                            <h2
                                class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2"
                            >
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 bg-teal-100 text-teal-600 rounded-full text-sm font-bold"
                                    >2</span
                                >
                                Academic Information
                            </h2>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8"
                            >
                                <div>
                                    <label
                                        for="department"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                        >Department *</label
                                    >
                                    <select
                                        id="department"
                                        name="department"
                                        required
                                        class="form-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                    >
                                        <option value="">
                                            Select Department
                                        </option>
                                        <option value="cse">
                                            Computer Science & Engineering
                                        </option>
                                        <option value="ece">
                                            Electronics & Communication
                                        </option>
                                        <option value="me">
                                            Mechanical Engineering
                                        </option>
                                        <option value="ce">
                                            Civil Engineering
                                        </option>
                                        <option value="ee">
                                            Electrical Engineering
                                        </option>
                                    </select>
                                    <span
                                        class="text-xs text-red-500 mt-1 hidden"
                                        id="departmentError"
                                        >Department is required</span
                                    >
                                </div>
                                <div>
                                    <label
                                        for="semester"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                        >Current Semester *</label
                                    >
                                    <select
                                        id="semester"
                                        name="semester"
                                        required
                                        class="form-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                    >
                                        <option value="">
                                            Select Semester
                                        </option>
                                        <option value="1">Semester 1</option>
                                        <option value="2">Semester 2</option>
                                        <option value="3">Semester 3</option>
                                        <option value="4">Semester 4</option>
                                        <option value="5">Semester 5</option>
                                        <option value="6">Semester 6</option>
                                        <option value="7">Semester 7</option>
                                        <option value="8">Semester 8</option>
                                    </select>
                                    <span
                                        class="text-xs text-red-500 mt-1 hidden"
                                        id="semesterError"
                                        >Semester is required</span
                                    >
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8"
                            >
                                <div>
                                    <label
                                        for="rollNumber"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                        >Roll Number *</label
                                    >
                                    <input
                                        type="text"
                                        id="rollNumber"
                                        name="rollNumber"
                                        required
                                        class="form-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                        placeholder="001"
                                    />
                                    <span
                                        class="text-xs text-red-500 mt-1 hidden"
                                        id="rollNumberError"
                                        >Roll number is required</span
                                    >
                                </div>
                                <div>
                                    <label
                                        for="batch"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                        >Batch/Year *</label
                                    >
                                    <select
                                        id="batch"
                                        name="batch"
                                        required
                                        class="form-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                    >
                                        <option value="">Select Batch</option>
                                        <option value="2024">2024</option>
                                        <option value="2023">2023</option>
                                        <option value="2022">2022</option>
                                        <option value="2021">2021</option>
                                    </select>
                                    <span
                                        class="text-xs text-red-500 mt-1 hidden"
                                        id="batchError"
                                        >Batch is required</span
                                    >
                                </div>
                            </div>

                            <div>
                                <label
                                    for="enrolledClasses"
                                    class="block text-sm font-medium text-slate-700 mb-3"
                                    >Assign Classes</label
                                >
                                <div
                                    class="bg-slate-50 border border-slate-200 rounded-lg p-4"
                                >
                                    <div class="space-y-3">
                                        <label
                                            class="flex items-center gap-3 cursor-pointer hover:bg-white p-2 rounded transition"
                                        >
                                            <input
                                                type="checkbox"
                                                name="classes"
                                                value="CSE101"
                                                class="w-4 h-4 text-emerald-600 rounded"
                                            />
                                            <span class="text-sm text-slate-700"
                                                >Data Structures (CSE101)</span
                                            >
                                        </label>
                                        <label
                                            class="flex items-center gap-3 cursor-pointer hover:bg-white p-2 rounded transition"
                                        >
                                            <input
                                                type="checkbox"
                                                name="classes"
                                                value="CSE102"
                                                class="w-4 h-4 text-emerald-600 rounded"
                                            />
                                            <span class="text-sm text-slate-700"
                                                >Web Development (CSE102)</span
                                            >
                                        </label>
                                        <label
                                            class="flex items-center gap-3 cursor-pointer hover:bg-white p-2 rounded transition"
                                        >
                                            <input
                                                type="checkbox"
                                                name="classes"
                                                value="CSE103"
                                                class="w-4 h-4 text-emerald-600 rounded"
                                            />
                                            <span class="text-sm text-slate-700"
                                                >Database Management
                                                (CSE103)</span
                                            >
                                        </label>
                                        <label
                                            class="flex items-center gap-3 cursor-pointer hover:bg-white p-2 rounded transition"
                                        >
                                            <input
                                                type="checkbox"
                                                name="classes"
                                                value="CSE104"
                                                class="w-4 h-4 text-emerald-600 rounded"
                                            />
                                            <span class="text-sm text-slate-700"
                                                >Artificial Intelligence
                                                (CSE104)</span
                                            >
                                        </label>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-500 mt-2">
                                    Select at least one class to enroll the
                                    student
                                </p>
                            </div>
                        </div>

                        <!-- Section Divider -->
                        <div class="section-divider">
                            <span>Additional Information</span>
                        </div>

                        <!-- Additional Section -->
                        <div>
                            <h2
                                class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2"
                            >
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 bg-cyan-100 text-cyan-600 rounded-full text-sm font-bold"
                                    >3</span
                                >
                                Additional Information
                            </h2>

                            <div class="mb-6">
                                <label
                                    for="address"
                                    class="block text-sm font-medium text-slate-700 mb-2"
                                    >Address</label
                                >
                                <textarea
                                    id="address"
                                    name="address"
                                    rows="3"
                                    class="form-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 resize-none"
                                    placeholder="Enter student's address"
                                ></textarea>
                            </div>

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8"
                            >
                                <div>
                                    <label
                                        for="city"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                        >City</label
                                    >
                                    <input
                                        type="text"
                                        id="city"
                                        name="city"
                                        class="form-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                        placeholder="New York"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="zipCode"
                                        class="block text-sm font-medium text-slate-700 mb-2"
                                        >Zip Code</label
                                    >
                                    <input
                                        type="text"
                                        id="zipCode"
                                        name="zipCode"
                                        class="form-input w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                                        placeholder="10001"
                                    />
                                </div>
                            </div>

                            <div
                                class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-8"
                            >
                                <label
                                    class="flex items-start gap-3 cursor-pointer"
                                >
                                    <input
                                        type="checkbox"
                                        id="agreeTerms"
                                        name="agreeTerms"
                                        required
                                        class="w-4 h-4 text-emerald-600 rounded mt-1"
                                    />
                                    <span class="text-sm text-slate-700"
                                        >I confirm that all the information
                                        provided is accurate and the student has
                                        agreed to the terms and conditions of
                                        the institution</span
                                    >
                                </label>
                                <span
                                    class="text-xs text-red-500 mt-2 hidden block"
                                    id="agreeTermsError"
                                    >You must agree to continue</span
                                >
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div
                            class="flex flex-col-reverse md:flex-row gap-4 justify-end"
                        >
                            <button
                                type="reset"
                                class="w-full md:w-auto px-6 py-2.5 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition"
                            >
                                Reset
                            </button>
                            <button
                                type="submit"
                                class="w-full md:w-auto px-8 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg font-medium hover:shadow-lg hover:shadow-emerald-200 transition"
                            >
                                Add Student
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Success State -->
                <div id="successContainer" class="hidden">
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 md:p-12 text-center"
                    >
                        <div
                            class="success-icon inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-6"
                        >
                            <svg
                                class="w-8 h-8 text-emerald-600"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">
                            Student Added Successfully!
                        </h2>
                        <p class="text-slate-600 mb-8">
                            The student has been registered in the system and
                            assigned to the selected classes.
                        </p>
                        <div
                            class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-8 text-left"
                        >
                            <p class="text-sm text-slate-700">
                                <strong>Student ID:</strong>
                                <span
                                    id="successStudentId"
                                    class="text-emerald-600 font-medium"
                                    >STU-2024-001</span
                                >
                            </p>
                            <p class="text-sm text-slate-700 mt-2">
                                <strong>Name:</strong>
                                <span
                                    id="successName"
                                    class="text-emerald-600 font-medium"
                                    >John Doe</span
                                >
                            </p>
                            <p class="text-sm text-slate-700 mt-2">
                                <strong>Department:</strong>
                                <span
                                    id="successDept"
                                    class="text-emerald-600 font-medium"
                                    >Computer Science</span
                                >
                            </p>
                        </div>
                        <div
                            class="flex flex-col sm:flex-row gap-4 justify-center"
                        >
                            <button
                                type="button"
                                onclick="
                                    location.href =
                                        'admin-student-management.html'
                                "
                                class="px-6 py-2.5 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg font-medium transition"
                            >
                                View All Students
                            </button>
                            <button
                                type="button"
                                onclick="resetForm()"
                                class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg font-medium hover:shadow-lg hover:shadow-emerald-200 transition"
                            >
                                Add Another Student
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Toast Notification -->
        <div
            id="toast"
            class="toast fixed bottom-4 right-4 bg-white border border-slate-200 rounded-lg shadow-lg p-4 hidden max-w-sm"
        >
            <div class="flex items-start gap-3">
                <div id="toastIcon" class="flex-shrink-0 mt-1"></div>
                <div>
                    <p
                        id="toastMessage"
                        class="text-sm font-medium text-slate-900"
                    ></p>
                </div>
            </div>
        </div>

        <script>
            // Mobile menu toggle
            const mobileMenuBtn = document.getElementById("mobileMenuBtn");
            const sidebar = document.getElementById("sidebar");
            const sidebarOverlay = document.getElementById("sidebarOverlay");

            mobileMenuBtn?.addEventListener("click", () => {
                sidebar.classList.toggle("hidden");
                sidebarOverlay.classList.toggle("hidden");
            });

            sidebarOverlay?.addEventListener("click", () => {
                sidebar.classList.add("hidden");
                sidebarOverlay.classList.add("hidden");
            });

            // Form handling
            const studentForm = document.getElementById("studentForm");
            const formContainer = document.getElementById("formContainer");
            const successContainer =
                document.getElementById("successContainer");
            const progressBar = document.getElementById("progressBar");

            // Update progress bar
            function updateProgress() {
                const formInputs = studentForm.querySelectorAll(
                    "input[required], select[required], textarea[required]",
                );
                const filledInputs = Array.from(formInputs).filter((input) => {
                    if (input.type === "checkbox") {
                        return input.checked;
                    }
                    return input.value.trim() !== "";
                }).length;
                const progress = (filledInputs / formInputs.length) * 100;
                progressBar.style.width = progress + "%";
            }

            studentForm.addEventListener("input", updateProgress);
            studentForm.addEventListener("change", updateProgress);

            // Form validation
            function validateForm() {
                let isValid = true;
                const errors = {};

                // Basic Info Validation
                const firstName = document
                    .getElementById("firstName")
                    .value.trim();
                if (!firstName) {
                    errors.firstName = true;
                    isValid = false;
                }

                const lastName = document
                    .getElementById("lastName")
                    .value.trim();
                if (!lastName) {
                    errors.lastName = true;
                    isValid = false;
                }

                const studentId = document
                    .getElementById("studentId")
                    .value.trim();
                if (!studentId) {
                    errors.studentId = true;
                    isValid = false;
                }

                const email = document.getElementById("email").value.trim();
                if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    errors.email = true;
                    isValid = false;
                }

                const dateOfBirth =
                    document.getElementById("dateOfBirth").value;
                if (!dateOfBirth) {
                    errors.dateOfBirth = true;
                    isValid = false;
                }

                // Academic Validation
                const department = document.getElementById("department").value;
                if (!department) {
                    errors.department = true;
                    isValid = false;
                }

                const semester = document.getElementById("semester").value;
                if (!semester) {
                    errors.semester = true;
                    isValid = false;
                }

                const rollNumber = document
                    .getElementById("rollNumber")
                    .value.trim();
                if (!rollNumber) {
                    errors.rollNumber = true;
                    isValid = false;
                }

                const batch = document.getElementById("batch").value;
                if (!batch) {
                    errors.batch = true;
                    isValid = false;
                }

                // Classes validation
                const checkedClasses = document.querySelectorAll(
                    'input[name="classes"]:checked',
                ).length;
                if (checkedClasses === 0) {
                    showToast("Select at least one class", "warning");
                    isValid = false;
                }

                // Terms agreement
                if (!document.getElementById("agreeTerms").checked) {
                    errors.agreeTerms = true;
                    isValid = false;
                }

                // Display errors
                Object.keys(errors).forEach((field) => {
                    const errorElement = document.getElementById(
                        field + "Error",
                    );
                    if (errorElement) {
                        errorElement.classList.remove("hidden");
                    }
                });

                return isValid;
            }

            // Form submission
            studentForm.addEventListener("submit", (e) => {
                e.preventDefault();

                if (!validateForm()) {
                    showToast("Please fix all errors and try again", "error");
                    return;
                }

                // Simulate API call
                const submitBtn = studentForm.querySelector(
                    'button[type="submit"]',
                );
                submitBtn.disabled = true;
                submitBtn.innerHTML = "Adding...";

                setTimeout(() => {
                    // Show success state
                    const firstName =
                        document.getElementById("firstName").value;
                    const lastName = document.getElementById("lastName").value;
                    const studentIdVal =
                        document.getElementById("studentId").value;
                    const deptVal =
                        document.getElementById("department").options[
                            document.getElementById("department").selectedIndex
                        ].text;

                    document.getElementById("successStudentId").textContent =
                        studentIdVal;
                    document.getElementById("successName").textContent =
                        firstName + " " + lastName;
                    document.getElementById("successDept").textContent =
                        deptVal;

                    formContainer.classList.add("hidden");
                    successContainer.classList.remove("hidden");

                    showToast("Student registered successfully!", "success");

                    submitBtn.disabled = false;
                    submitBtn.innerHTML = "Add Student";
                }, 1000);
            });

            function resetForm() {
                studentForm.reset();
                formContainer.classList.remove("hidden");
                successContainer.classList.add("hidden");
                progressBar.style.width = "0%";
                document
                    .querySelectorAll(".text-red-500")
                    .forEach((el) => el.classList.add("hidden"));
            }

            // Toast notification
            function showToast(message, type = "info") {
                const toast = document.getElementById("toast");
                const toastMessage = document.getElementById("toastMessage");
                const toastIcon = document.getElementById("toastIcon");

                toastMessage.textContent = message;

                let iconHTML = "";
                if (type === "success") {
                    iconHTML =
                        '<svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
                } else if (type === "error") {
                    iconHTML =
                        '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
                } else if (type === "warning") {
                    iconHTML =
                        '<svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
                } else {
                    iconHTML =
                        '<svg class="w-5 h-5 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zm5 0a1 1 0 11-2 0 1 1 0 012 0zm4 0a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path></svg>';
                }

                toastIcon.innerHTML = iconHTML;
                toast.classList.remove("hidden");

                setTimeout(() => {
                    toast.classList.add("hidden");
                }, 4000);
            }
        </script>
    </body>
</html>
