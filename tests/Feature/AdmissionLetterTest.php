<?php

use App\Models\Candidate;
use App\Models\Course;
use App\Models\Faculty;

function makeAdmissionLetterCandidate(string $jamb): Candidate
{
    $faculty = Faculty::create([
        'name' => 'Faculty of Public Relations',
        'code' => 'FPR',
    ]);

    $course = Course::create([
        'faculty_id' => $faculty->id,
        'name' => 'Mass Communication',
        'code' => 'MAS',
    ]);

    return Candidate::create([
        'jamb_reg_number' => $jamb,
        'surname' => 'Doe',
        'first_name' => 'John',
        'other_names' => 'Paul',
        'gender' => 'male',
        'date_of_birth' => '2000-01-01',
        'state_of_origin' => 'Lagos',
        'local_government' => 'Ikeja',
        'utme_score' => 250,
        'course_id' => $course->id,
        'status' => 'admitted',
    ]);
}

test('admitted candidate can download their admission letter as a PDF', function () {
    $candidate = makeAdmissionLetterCandidate('12345678AB');

    $response = $this
        ->actingAs($candidate, 'candidate')
        ->get(route('candidate.admission.letter.download'));

    $response->assertOk();
    $response->assertHeader('content-type', 'application/pdf');
    $response->assertHeader('content-disposition');
});

test('candidate who is not yet admitted cannot download an admission letter', function () {
    $candidate = makeAdmissionLetterCandidate('12345678AB');
    $candidate->update(['status' => 'recommended_for_admission']);

    $response = $this
        ->actingAs($candidate, 'candidate')
        ->get(route('candidate.admission.letter.download'));

    $response->assertSessionHasErrors();
});
