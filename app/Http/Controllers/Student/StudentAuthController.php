<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentAuthController extends Controller
{
    public function create()
    {
        return view('student.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'student_email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (auth('student')->attempt([
            'student_email' => $request->student_email,
            'password' => $request->password,
        ])) {
            return redirect()->route('student.dashboard');
        }

        return back()->withErrors([
            'student_email' => 'Invalid credentials.',
        ]);
    }

    public function logout()
    {

        auth('student')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('student.login');
    }
}
