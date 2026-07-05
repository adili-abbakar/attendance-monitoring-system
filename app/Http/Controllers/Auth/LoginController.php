<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }



    public function loginAttempt(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {

            if (!Auth::attempt($credentials)) {
                return back()
                    ->withInput($request->only('email'))
                    ->with('error', 'Invalid email or password.');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login successful.');
        } catch (\Exception $e) {

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->intended(route('home'))->with('success', "Logged Out Sucessfully");
    }
}
