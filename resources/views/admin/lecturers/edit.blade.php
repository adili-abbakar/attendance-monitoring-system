@extends('layouts.admin')

@section('page-header')
    <x-page-header
        title="Edit Lecturer"
        subtitle="Update lecturer account and profile information"
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('admin.dashboard')],
            ['label' => 'Lecturers', 'url' => route('admin.lecturers.index')],
            ['label' => $lecturer->user->name, 'url' => route('admin.lecturers.show', $lecturer)],
            ['label' => 'Edit', 'url' => null],
        ]"
        :actions="
            <a href='{{ route('admin.lecturers.show', $lecturer) }}'>
                <x-button variant='secondary'>Back to Details</x-button>
            </a>
        "
    />
@endsection

@section('page-content')
    <x-card>
        <form method="POST" action="{{ route('admin.lecturers.update', $lecturer) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- User Account Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-teal-100 text-teal-600 rounded-full flex items-center justify-center text-sm font-bold">1</span>
                    Account Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input label="Full Name" name="name" type="text" required :value="$lecturer->user->name" :error="$errors->first('name')" />
                    <x-input label="Email Address" name="email" type="email" required :value="$lecturer->user->email" :error="$errors->first('email')" />
                    <x-input label="Staff Number" name="staff_number" type="text" required :value="$lecturer->staff_number" :error="$errors->first('staff_number')" help="Unique staff identification number" />
                    <x-input label="Phone Number" name="phone" type="tel" :value="$lecturer->phone" placeholder="+1 (555) 123-4567" :error="$errors->first('phone')" />
                    
                    <div class="md:col-span-2">
                        <x-select label="Gender" name="gender" :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']" :value="$lecturer->gender" placeholder="Select gender" :error="$errors->first('gender')" />
                    </div>
                </div>

                <div class="mt-4">
                    <x-input label="Date of Birth" name="date_of_birth" type="date" :value="$lecturer->date_of_birth?->format('Y-m-d')" :error="$errors->first('date_of_birth')" />
                </div>
            </div>

            <!-- Profile Information Section -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-bold">2</span>
                    Profile Information
                </h3>

                <div class="grid grid-cols-1 gap-6">
                    <x-input label="Address" name="address" type="text" :value="$lecturer->address" placeholder="123 University Avenue, City, State" :error="$errors->first('address')" />
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="border-b border-gray-200 pb-6 bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-bold">3</span>
                    Change Password (Optional)
                </h3>
                <p class="text-sm text-gray-600 mb-4">Leave blank to keep current password.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input label="New Password" name="password" type="password" placeholder="••••••••" :error="$errors->first('password')" help="Minimum 8 characters" />
                    <x-input label="Confirm New Password" name="password_confirmation" type="password" placeholder="••••••••" :error="$errors->first('password_confirmation')" />
                </div>
            </div>

            <!-- Status Section -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-bold">4</span>
                    Account Status
                </h3>

                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="status" value="1" {{ $lecturer->user->status ? 'checked' : '' }} class="w-4 h-4 text-teal-600 rounded focus:ring-teal-500">
                        <span class="text-sm text-gray-700">Active (lecturer can log in and access API)</span>
                    </label>
                </div>

                @if (!$lecturer->user->status)
                    <p class="text-sm text-yellow-600 mt-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Inactive lecturers cannot log in or use the Flutter app API.
                    </p>
                @endif
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col-reverse sm:flex-row gap-4 justify-end pt-4 border-t border-gray-200">
                <a href="{{ route('admin.lecturers.show', $lecturer) }}" class="w-full sm:w-auto">
                    <x-button variant="secondary">Cancel</x-button>
                </a>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save Changes
                </x-button>
            </div>
        </form>
    </x-card>
@endsection