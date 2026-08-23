<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\OlevelResult;
use App\Models\OlevelSubjectGrade;
use Illuminate\Http\Request;

class OlevelController extends Controller
{
    public function create()
    {
        $candidate = auth('candidate')->user();

        // Prevent re-entry if already submitted
        if ($candidate->olevelResult) {
            return redirect()->route('candidate.dashboard')
                ->with('error', 'O\'Level result already submitted.');
        }

        return view('candidate.olevel.create');

    }

    public function store(Request $request)
    {

        $candidate = auth('candidate')->user();

        $validatedData = $request->validate([
            'exam_type' => ['required', 'in:WAEC,NECO,NABTEB'],
            'exam_year' => ['required', 'digits:4'],
            'exam_number' => ['required', 'string', 'max:50'],
            'scratch_card_or_token' => ['required', 'string', 'max:50'],
            'subjects' => ['required', 'array', 'min:5'],
            'subjects.*.subject_name' => ['required', 'string'],
            'subjects.*.grade' => ['required', 'string', 'in:A1,B2,B3,C4,C5,C6,D7,E8,F9'],
        ]);

        $olevelResult = OlevelResult::create(
            [
                'candidate_id' => $candidate->id,
                'exam_type' => $validatedData['exam_type'],
                'exam_year' => $validatedData['exam_year'],
                'exam_number' => $validatedData['exam_number'],
                'scratch_card_or_token' => $validatedData['scratch_card_or_token'],
                'is_verified' => false,
            ]
        );

        foreach ($validatedData['subjects'] as $subject) {
            OlevelSubjectGrade::create([
                'olevel_result_id' => $olevelResult->id,
                'subject_name' => $subject['subject_name'],
                'grade' => $subject['grade'],
            ]);

        }

        return redirect()->route('candidate.olevel.verify')
            ->with('success', 'O\'Level details submitted. Proceed to verification.');
    }

    public function verify()
    {
        $candidate = auth('candidate')->user();
        $olevelResult = $candidate->olevelResult;

        if (! $olevelResult) {
            return redirect()->route('candidate.olevel.create');
        }

        return view('candidate.olevel.verify', ['olevelResult' => $olevelResult]);
    }

    public function confirmVerification()
    {
        $candidate = auth('candidate')->user();
        $olevelResult = $candidate->olevelResult;

        $olevelResult->update(['is_verified' => true]);

        return redirect()->route('candidate.dashboard')
            ->with('success', 'O\'Level result verified successfully.');
    }
}
