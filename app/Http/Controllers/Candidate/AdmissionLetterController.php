<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

class AdmissionLetterController extends Controller
{
    public function download()
    {

        $candidate = auth('candidate')->user();

        if ($candidate->status !== 'admitted' && $candidate->status !== 'acceptance_confirmed') {
            return back()->withErrors('error', 'Admission letter is only available once you have been admitted');
        }

        $pdf = Pdf::loadView('candidate.admission.letter', [
            'candidate' => $candidate,
        ]);

        return $pdf->download('admission-letter-'.$candidate->jamb_reg_number.'.pdf');
    }
}
