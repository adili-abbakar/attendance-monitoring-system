<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    public function registerAttempt(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:100|unique:users,email',
            'account_type' => 'required|in:admin,lecturer,student',
            'password' => 'required|string|min:8|max:255|confirmed',
        ]);

        try {

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['account_type'],
            ]);

            return redirect()->route('login')
                ->with('success', 'Registered successfully.');
        } catch (\Exception $e) {

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
