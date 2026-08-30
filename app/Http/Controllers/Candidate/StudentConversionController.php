<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Str;

class StudentConversionController extends Controller
{
    public function convert()
    {
        $candidate = auth('candidate')->user();

        if ($candidate->status !== 'acceptance_confirmed') {
            return back()->with('error', 'Student account can only be created after acceptance fee payment.');
        }

        if ($candidate->student) {
            return redirect()->route('candidate.dashboard')->with('error', 'Student account already exists.');
        }

        $course = $candidate->course;
        $matricNumber = $this->generateMatricNumber($course);
        $studentEmail = strtolower($candidate->first_name.'.'.$candidate->surname.rand(10, 99).'@student.upr.edu.ng');

        $plainPassword = Str::random(10);

        Student::create([
            'candidate_id' => $candidate->id,
            'faculty_id' => $course->faculty_id,
            'department_id' => $course->department_id,
            'matric_number' => $matricNumber,
            'student_email' => $studentEmail,
            'password' => $plainPassword,
        ]);

        $candidate->update(['status' => 'student']);

        return redirect()->route('candidate.dashboard')->with([
            'success' => 'Your student account has been created!',
            'generated_password' => $plainPassword,
        ]);

    }

    protected function generateMatricNumber($course): string
    {
        $year = now()->format('Y');
        $courseCode = $course->code ?? 'GEN';
        $sequence = Student::whereYear('created_at', $year)->count() + 1;

        return "UPR/{$year}/{$courseCode}/".str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
