@extends('layouts.app')


@section('content')
    <body>
        <style>
            :root {
                --primary: #10b981;
                --primary-dark: #059669;
                --primary-light: #d1fae5;
                --secondary: #14b8a6;
                --neutral-50: #f9fafb;
                --neutral-900: #111827;
                --neutral-700: #374151;
                --neutral-600: #4b5563;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
                background: linear-gradient(135deg, #f0fdf4 0%, #f0fdfa 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 1rem;
            }

            .login-container {
                width: 100%;
                max-width: 400px;
                background: white;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                padding: 2rem;
                animation: slideUp 0.4s ease-out;
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .logo-section {
                text-align: center;
                margin-bottom: 2rem;
            }

            .logo {
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 700;
                font-size: 1.5rem;
                margin: 0 auto 1rem;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
            }

            .logo-text {
                color: var(--neutral-900);
                font-size: 1.5rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .logo-subtext {
                color: var(--neutral-600);
                font-size: 0.875rem;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-group label {
                display: block;
                color: var(--neutral-700);
                font-weight: 500;
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
            }

            .form-group input {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                font-size: 1rem;
                transition: all 0.2s ease;
                background: var(--neutral-50);
            }

            .form-group input:focus {
                outline: none;
                border-color: var(--primary);
                background: white;
                box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            }

            .form-group input::placeholder {
                color: var(--neutral-600);
            }

            .remember-forgot {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 1.5rem;
                font-size: 0.875rem;
            }

            .checkbox-wrapper {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .checkbox-wrapper input[type="checkbox"] {
                width: 18px;
                height: 18px;
                cursor: pointer;
                accent-color: var(--primary);
            }

            .checkbox-wrapper label {
                margin: 0;
                color: var(--neutral-700);
                cursor: pointer;
            }

            .forgot-password {
                color: var(--primary);
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .forgot-password:hover {
                color: var(--primary-dark);
            }

            .signin-btn {
                width: 100%;
                padding: 0.875rem 1rem;
                background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 1rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
            }

            .signin-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
            }

            .signin-btn:active {
                transform: translateY(0);
            }

            .divider {
                display: flex;
                align-items: center;
                margin: 1.5rem 0;
                color: var(--neutral-600);
                font-size: 0.875rem;
            }

            .divider::before,
            .divider::after {
                content: '';
                flex: 1;
                height: 1px;
                background: #e5e7eb;
            }

            .divider::before {
                margin-right: 1rem;
            }

            .divider::after {
                margin-left: 1rem;
            }

            .role-selector {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.75rem;
                margin-bottom: 1.5rem;
            }

            .role-option {
                padding: 1rem;
                border: 2px solid #e5e7eb;
                border-radius: 8px;
                text-align: center;
                cursor: pointer;
                transition: all 0.2s ease;
                background: white;
            }

            .role-option:hover {
                border-color: var(--primary);
                background: var(--primary-light);
            }

            .role-option.active {
                border-color: var(--primary);
                background: var(--primary);
                color: white;
            }

            .role-option small {
                display: block;
                font-size: 0.75rem;
                margin-top: 0.25rem;
                opacity: 0.8;
            }

            .footer-text {
                text-align: center;
                color: var(--neutral-600);
                font-size: 0.875rem;
                margin-top: 1.5rem;
            }

            .footer-text a {
                color: var(--primary);
                text-decoration: none;
            }

            .footer-text a:hover {
                text-decoration: underline;
            }

            @media (max-width: 480px) {
                .login-container {
                    padding: 1.5rem;
                    border-radius: 16px;
                }

                .logo-text {
                    font-size: 1.25rem;
                }
            }
        </style>
        @yield('page-content')
    </body>
@endsection
