<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ScreeningReportController extends Controller
{
    public function download()
    {
        $candidate = Auth::guard('candidate')->user();
        $screeningReport = $candidate->screeningReports()->latest('generated_at')->first();

        if (! $screeningReport) {
            return back()->with('error', 'No screening report found yet.');
        }

        $pdf = Pdf::loadView('candidate.screening.report', [
            'candidate' => $candidate,
            'report' => $screeningReport,
        ]);

        return $pdf->download('screening-report-'.$candidate->jamb_reg_number.'.pdf');
    }
}
