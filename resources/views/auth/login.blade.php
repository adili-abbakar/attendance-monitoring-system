@extends('layouts.guest')
@section('title', 'Login')
@section('page-content')
    <div class="login-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo">QR</div>
            <h1 class="logo-text">AttendanceHub</h1>
            <p class="logo-subtext">University Attendance System</p>
        </div>

        <!-- Login Form -->
        <form id="loginForm" action="{{ route('login.attempt') }}" method="POST">
            @csrf
            @if (session('error'))
                <div class="form-group">
                    <div class="border border-red-600 rounded-lg p-4! text-center font-bold text-red-600">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label for="email">Email Address</label>

                <input type="email" id="email" name="email" placeholder="your@university.edu"
                    value="{{ old('email') }}">

                @error('email')
                    <small style="color:red">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <input type="password" id="password" name="password" placeholder="••••••••">

                @error('password')
                    <small style="color:red">{{ $message }}</small>
                @enderror
            </div>

            <div class="remember-forgot">
                <div class="checkbox-wrapper">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="#" class="forgot-password">Forgot password?</a>
            </div>

            <button type="submit"
                class="w-full bg-linear-to-r from-teal-500 to-green-500 text-white font-semibold py-3! rounded-lg btn-hover mb-4 disabled:opacity-50"
                >Sign In</button>
        </form>

        <!-- Divider -->
        <div class="divider">OR</div>

        <!-- Demo Login Note -->
        <p class="footer-text" style="margin-top: 1rem;">
            Don't Have Account? <a href="{{ route('register') }}">Register Now</a> <br>
            <small style="display: block; margin-top: 0.5rem; opacity: 0.7;">This is a prototype interface</small>
        </p>
    </div>
@endsection
