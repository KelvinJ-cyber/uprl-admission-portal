<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Course;
use App\Services\CourseEligibilityService;
use Illuminate\Http\Request;

class EligibilityCheckController extends Controller
{
    protected CourseEligibilityService $eligibilityCheckService;

    public  function __construct(CourseEligibilityService $eligibilityCheckService)
    {
        $this->eligibilityCheckService = $eligibilityCheckService;
    }

    public function create()
    {
        $courses = Course::orderBy('name')->get();

        return view('eligibility.check', ['courses' => $courses]);
    }

    public function check(Request $request)
    {
        $validated = $request->validate([
            'jamb_reg_number' => ['required', 'string'],
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $candidate = Candidate::where('jamb_reg_number', $validated['jamb_reg_number'])->first();

        if (! $candidate) {
            return back()->withErrors([
                'jamb_reg_number' => 'No candidate found with this JAMB registration number.',
            ]);

        }
        $course = Course::findOrFail($validated['course_id']);

        $result = $this->eligibilityCheckService->checkEligibility($candidate, $course);

        return view('eligibility.result', [
            'candidate' => $candidate,
            'course' => $course,
            'result' => $result,
        ]);

    }

}
