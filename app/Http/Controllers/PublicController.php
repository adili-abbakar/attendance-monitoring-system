<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    public function home()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            // For lecturers, redirect to API documentation or show a message
            if (Auth::user()->role === 'lecturer') {
                return view('public.lecturer-dashboard');
            }
        }

        return view("public.home");
    }
}
