<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Course;
use Illuminate\Http\Request;

class AdmissionsController extends Controller
{
    public function index(Request $request)
    {
        // query candidates with their course and faculty
        $query = Candidate::with(['course','course.faculty']);

        // Apply filters based on request parameters. what this does is that it checks if the request has a parameter called 'course_id',
        // and if it does, it filters the candidates to only include those who have that course_id. It does the same for 'faculty_id', 'state_of_origin', 'min_utme_score', and 'status'.
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('faculty_id')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('faculty_id', $request->faculty_id);
            });
        }

        if ($request->filled('state_of_origin')) {
            $query->where('state_of_origin', $request->state_of_origin);
        }

        if ($request->filled('min_utme_score')) {
            $query->where('utme_score', '>=', $request->min_utme_score);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $candidates = $query->orderBy('surname')->paginate(20)->withQueryString();

        $courses = Course::orderBy('name')->get();

        return view('staff.admissions.index', [
            'candidates' => $candidates,
            'courses' => $courses,
        ]);

    }

    public function recommend(Candidate $candidate)
    {
        if ($candidate->status !== 'screening_passed') {
            return back()->with('error', 'Only candidates who passed screening can be recommended.');
        }

        $candidate->update([
            'status' => 'recommended_for_admission',
            'recommended_by' => auth('web')->id(),
            'recommended_at' => now(),
        ]);

        return back()->with('success', "{$candidate->first_name} {$candidate->surname} has been recommended for admission.");
    }
}
