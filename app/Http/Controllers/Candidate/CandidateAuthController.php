<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateAuthController extends Controller
{
    public function create()
    {
        return view('candidate.login');
    }


    public function login(Request $request)
    {
        $request->validate([
            'jamb_reg_number' => ['required', 'string'],
        ]);

        $candidate = Candidate::where('jamb_reg_number', $request->jamb_reg_number)->first();

        if (! $candidate) {
            return back()->withErrors([
                'jamb_reg_number' => 'No record found for this JAMB registration number.',
            ]);
        }

        Auth::guard('candidate')->login($candidate);

        return redirect()->route('candidate.dashboard');
    }

    public function destroy(Request $request)
    {
        Auth::guard('candidate')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('candidate.login');
    }
}
