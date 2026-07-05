@extends('layouts.guest')
@section('title', 'Register')
@section('page-content')
    <div class="w-full max-w-2xl">
        <div class="bg-white rounded-2xl shadow-lg p-8! card-enter">
            <!-- Logo and Branding -->
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-linear-to-br from-teal-400 to-green-500 rounded-full mb-4">
                    <span class="text-white text-2xl font-bold">📚</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">University</h1>
                <p class="text-gray-500 text-sm mt-1">Attendance Management System</p>
            </div>

            <!-- Form Container -->
            <div id="formContainer" style="display: block;">

                <!-- Heading -->
                <div class="mb-8 text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Create your account</h2>
                    <p class="text-gray-600 text-sm">Join the attendance management system</p>
                </div>

                <!-- Registration Form -->
                <form id="registerForm" method="post" action="{{ route('register.attempt') }}">
                    @csrf
                    @if (session('error'))
                        <div class="form-group">
                            <div class="border border-red-600 rounded-lg p-4! text-center font-bold text-red-600">
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif


                    <!-- First and Last Name -->
                    <div class="form-group">
                        <div class="form-row">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">First
                                Name</label>
                            <input type="text" id="name" name="name" placeholder="Name Surname"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent input-focus transition"
                                value="{{ old('name') }}">

                            @error('name')
                                <small class="text-red-600 text-xs mt-2">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="user@university.edu"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent input-focus transition"
                            value="{{ old('email') }}">

                        @error('email')
                            <small class="text-red-600 text-xs mt-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Department (for lecturer) -->
                    <div class="form-group mb-6" id="departmentGroup">
                        <label for="account_type" class="block text-sm font-medium text-gray-700 mb-2">Account Type</label>
                        <select id="account_type" name="account_type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent input-focus transition">
                            <option value="">Select accout type</option>
                            <option value="admin">Admin</option>
                            <option value="lecturer">Lecturer</option>
                            <option value="student">Student</option>
                        </select>
                        @error('account_type')
                            <small class="text-red-600 text-xs mt-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Create a strong password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent input-focus transition pr-10">
                            <button type="button" onclick="togglePasswordVisibility('password')"
                                class="absolute right-3 top-3 toggle-password">
                                <svg class="w-5 h-5" id="passwordIcon" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <small class="text-red-600 text-xs mt-2">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm
                            Password</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Confirm password" oninput="checkPasswordMatch()"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent input-focus transition pr-10">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation')"
                                class="absolute right-3 top-3 toggle-password">
                                <svg class="w-5 h-5" id="password_confirmationIcon" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <small class="text-red-600 text-xs mt-2">{{ $message }}</small>
                        @enderror

                    </div>

                    <!-- Terms and Conditions -->
                    <div class="form-group mb-6">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" id="terms" name="terms"
                                class="mt-1 w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                            <span class="text-sm text-gray-600">
                                I agree to the <a href="#" class="text-green-600 font-semibold link-hover">Terms and
                                    Conditions</a> and <a href="#"
                                    class="text-green-600 font-semibold link-hover">Privacy
                                    Policy</a>
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn"
                        class="w-full bg-linear-to-r from-teal-500 to-green-500 text-white font-semibold py-3! rounded-lg btn-hover mb-4 disabled:opacity-50">
                        Create Account
                    </button>

                    <!-- Login Link -->
                    <p class="text-center text-gray-600 text-sm">
                        Already have an account? <a href="{{ route('login') }}"
                            class="text-green-600 font-semibold link-hover">Log
                            in</a>
                    </p>
                </form>
            </div>


        </div>
    </div>

    <script>
        /**
         * Toggle password visibility
         */
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + 'Icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803m5.596-3.856a3.375 3.375 0 11-4.753 4.753m4.753-4.753L9.172 9.172"/>';
            } else {
                field.type = 'password';
                icon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
    </script>
@endsection
