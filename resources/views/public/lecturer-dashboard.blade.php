@extends('layouts.app')

@section('title', 'Lecturer Dashboard')

@section('page-content')
    <div class="flex h-screen overflow-hidden">
        <x-sidebar />
        <x-loader />
        <x-mobile-header />
        <x-mobile-drawer />
        <main class="flex-1 overflow-auto md:mt-0 mt-16">
            <div class="p-4 md:p-8 max-w-3xl mx-auto">
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Lecturer Dashboard</h1>
                    <p class="text-gray-600 text-lg mb-8 max-w-xl mx-auto">
                        Welcome back, {{ auth()->user()->name }}. This web portal is for administrators only.
                    </p>
                    
                    <div class="bg-teal-50 border border-teal-200 rounded-xl p-6 mb-8">
                        <h3 class="text-lg font-semibold text-teal-800 mb-3">Attendance Operations</h3>
                        <p class="text-teal-700 mb-4">
                            As a lecturer, all attendance operations are performed through the <strong>Flutter mobile application</strong>.
                        </p>
                        <ul class="text-teal-700 space-y-2 text-left max-w-md mx-auto">
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> QR code scanning for attendance</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Lecture session management</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Student enrolment</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Offline synchronization</li>
                            <li class="flex items-center gap-2"><svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg> Attendance reports</li>
                        </ul>
                    </div>

                    <p class="text-gray-500 text-sm">
                        Please use the UniAttend Flutter app on your mobile device for all attendance-related tasks.
                    </p>
                </div>
            </div>
        </main>
    </div>
@endsection