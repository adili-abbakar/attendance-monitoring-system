@extends('layouts.guest')
@section('title', 'Login')
@section('page-content')
    <x-loader />
    <div class="login-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo">QR</div>
            <h1 class="logo-text">AttendanceHub</h1>
            <p class="logo-subtext">University Attendance System</p>
        </div>

        <!-- Login Form -->
        <form id="authForm" action="{{ route('login.attempt') }}" method="POST">
            @csrf
            <!-- Global error placeholder -->
            <div class="form-group">

                <div id="globalError" class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out mb-4">
                    <div
                        class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 px-4! py-3! rounded-lg">
                        <svg class="w-5 h-5" role="img" viewBox="0 0 24 24">
                            <g fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M13.253 5.98L12 13.5l-1.253-7.52a1.27 1.27 0 1 1 2.506 0"></path>
                                <circle cx="12" cy="19" r="1" stroke-width="2"></circle>
                            </g>
                        </svg>
                        <span class="text-sm font-medium"></span>
                    </div>
                </div>
            </div>  
            <div class="form-group">
                <label for="email">Email Address</label>

                <input type="text" id="email" name="email" placeholder="your@university.edu">
                <span id="emailError" class="text-red-600 text-sm"></span>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••">
                <span id="passwordError" class="text-red-600 text-sm"></span>
            </div>

            <div class="remember-forgot">
                <div class="checkbox-wrapper">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="#" class="forgot-password">Forgot password?</a>
            </div>

            <button type="submit"
                class="w-full bg-linear-to-r from-teal-500 to-green-500 text-white font-semibold py-3! rounded-lg btn-hover mb-4 disabled:opacity-50">Sign
                In</button>
        </form>

        <!-- Divider -->
        <div class="divider">OR</div>

        <!-- Demo Login Note -->
        <p class="footer-text" style="margin-top: 1rem;">
            Don't Have Account? <a href="{{ route('register') }}">Register Now</a> <br>
            <small style="display: block; margin-top: 0.5rem; opacity: 0.7;">This is a prototype interface</small>
        </p>
    </div>
    <script src="{{ asset('js/authFormSubmitter.js') }}"></script>
@endsection
