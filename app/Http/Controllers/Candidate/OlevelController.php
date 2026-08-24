<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\OlevelResult;
use App\Models\OlevelSubjectGrade;
use App\Models\ScreeningReport;
use App\Services\CourseEligibilityService;
use Illuminate\Http\Request;

class OlevelController extends Controller
{

    protected CourseEligibilityService $courseEligibilityService;

    public function __construct(CourseEligibilityService $courseEligibilityService)
    {
        $this->courseEligibilityService = $courseEligibilityService;
    }
    public function create()
    {
        $candidate = auth('candidate')->user();

        if ($candidate->status === 'screening_passed') {
            return redirect()->route('candidate.dashboard')
                ->with('error', 'You have already passed screening.');
        }

        return view('candidate.olevel.create');

    }

    public function store(Request $request)
    {
        $candidate = auth('candidate')->user();

        $validated = $request->validate([
            'exam_type' => ['required', 'in:WAEC,NECO,NABTEB'],
            'exam_year' => ['required', 'digits:4'],
            'exam_number' => ['required', 'string', 'max:50'],
            'scratch_card_or_token' => ['required', 'string', 'max:50'],
            'subjects' => ['required', 'array', 'min:5'],
            'subjects.*.subject_name' => ['required', 'string'],
            'subjects.*.grade' => ['required', 'string', 'in:A1,B2,B3,C4,C5,C6,D7,E8,F9'],
        ]);

        $olevelResult = OlevelResult::updateOrCreate(
            ['candidate_id' => $candidate->id],
            [
                'exam_type' => $validated['exam_type'],
                'exam_year' => $validated['exam_year'],
                'exam_number' => $validated['exam_number'],
                'scratch_card_or_token' => $validated['scratch_card_or_token'],
                'is_verified' => false,
            ]
        );

        // Clear old subject/grade rows before adding the new ones
        $olevelResult->subjectGrades()->delete();

        foreach ($validated['subjects'] as $subject) {
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

        $result = $this->courseEligibilityService->checkEligibility($candidate, $candidate->course);
        $status = $result['eligible'] ? 'screening_passed' : 'screening_pending';
        $deficiencyReason = $result['eligible'] ? null : implode(' ', $result['reasons']);

        ScreeningReport::create([
            'candidate_id' => $candidate->id,
            'status' => $status,
            'deficiency_reason' => $deficiencyReason,
            'generated_at' => now(),
        ]);

        $candidate->update(['status' => $status]);
        $message = $result['eligible']
            ? 'Congratulations! You have passed screening.'
            : 'Screening incomplete: ' . $deficiencyReason;

        return redirect()->route('candidate.dashboard')->with(
            $result['eligible'] ? 'success' : 'error',
            $message
        );
    }
}
