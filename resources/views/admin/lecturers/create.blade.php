@extends('layouts.admin')

@section('page-header')
    <x-page-header
        title="Add Lecturer"
        subtitle="Create a new lecturer account with profile"
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Lecturers', 'url' => route('admin.lecturers.index')],
            ['label' => 'Add Lecturer', 'url' => null],
        ]"
    />
@endsection

@section('page-content')
    <x-card>
        <form method="POST" action="{{ route('admin.lecturers.store') }}" class="space-y-6">
            @csrf

            <!-- User Account Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-sm font-bold">1</span>
                    Account Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input label="Full Name" name="name" type="text" required placeholder="Dr. John Smith" :error="$errors->first('name')" />
                    <x-input label="Email Address" name="email" type="email" required placeholder="john.smith@university.edu" :error="$errors->first('email')" />
                    <x-input label="Staff Number" name="staff_number" type="text" required placeholder="STF-001" :error="$errors->first('staff_number')" help="Unique staff identification number" />
                    <x-input label="Phone Number" name="phone" type="tel" placeholder="+1 (555) 123-4567" :error="$errors->first('phone')" />
                    <x-input label="Password" name="password" type="password" required placeholder="••••••••" :error="$errors->first('password')" help="Minimum 8 characters" />
                    <x-input label="Confirm Password" name="password_confirmation" type="password" required placeholder="••••••••" :error="$errors->first('password_confirmation')" />
                </div>

                <div class="mt-4">
                    <x-select label="Gender" name="gender" :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']" placeholder="Select gender" :error="$errors->first('gender')" />
                </div>

                <div class="mt-4">
                    <x-input label="Date of Birth" name="date_of_birth" type="date" :error="$errors->first('date_of_birth')" />
                </div>
            </div>

            <!-- Profile Information Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold">2</span>
                    Profile Information
                </h3>

                <div class="grid grid-cols-1 gap-6">
                    <x-input label="Address" name="address" type="text" placeholder="123 University Avenue, City, State" :error="$errors->first('address')" />
                </div>
            </div>

            <!-- Status Section -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-bold">3</span>
                    Account Status
                </h3>

                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="status" value="1" checked class="w-4 h-4 text-teal-600 rounded focus:ring-teal-500">
                        <span class="text-sm text-gray-700">Active (lecturer can log in and access API)</span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col-reverse sm:flex-row gap-4 justify-end pt-4 border-t border-gray-200">
                <a href="{{ route('admin.lecturers.index') }}" class="w-full sm:w-auto">
                    <x-button variant="secondary">Cancel</x-button>
                </a>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Create Lecturer
                </x-button>
            </div>
        </form>
    </x-card>
@endsection