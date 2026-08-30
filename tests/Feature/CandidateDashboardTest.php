<?php

use App\Models\Candidate;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Student;

function makeStepperCandidate(string $jamb): Candidate
{
    return Candidate::create([
        'jamb_reg_number' => $jamb,
        'surname' => 'Doe',
        'first_name' => 'John',
        'gender' => 'male',
        'date_of_birth' => '2000-01-01',
        'state_of_origin' => 'Lagos',
        'local_government' => 'Ikeja',
        'utme_score' => 250,
    ]);
}

test('dashboard stepper renders all seven stages', function () {
    $candidate = makeStepperCandidate('12345678AB');

    $response = $this
        ->actingAs($candidate, 'candidate')
        ->get(route('candidate.dashboard'));

    $response->assertOk();
    $response->assertSee('Screening');
    $response->assertSee('Payment');
    $response->assertSee('Screening Result');
    $response->assertSee('Documents Verified');
    $response->assertSee('Recommended');
    $response->assertSee('Sent to JAMB');
    $response->assertSee('Admitted');
});

test('dashboard stepper notes incomplete screening for screening pending candidates', function () {
    $candidate = makeStepperCandidate('12345678AB');
    $candidate->update(['status' => 'screening_pending']);

    $response = $this
        ->actingAs($candidate, 'candidate')
        ->get(route('candidate.dashboard'));

    $response->assertOk();
    $response->assertSee('Screening incomplete — see dashboard for details');
});

test('dashboard stepper marks the final stage as celebratory when admitted', function () {
    $candidate = makeStepperCandidate('12345678AB');
    $candidate->update(['status' => 'admitted']);

    $response = $this
        ->actingAs($candidate, 'candidate')
        ->get(route('candidate.dashboard'));

    $response->assertOk();
    $response->assertSee('bg-emerald-600');
    $response->assertSee('font-bold text-emerald-700');
});

test('dashboard stepper does not celebrate before admission', function () {
    $candidate = makeStepperCandidate('12345678AB');
    $candidate->update(['status' => 'recommended_for_admission']);

    $response = $this
        ->actingAs($candidate, 'candidate')
        ->get(route('candidate.dashboard'));

    $response->assertOk();
    $response->assertDontSee('bg-emerald-600');
});

test('dashboard shows informational notice when admission is pending with JAMB', function () {
    $candidate = makeStepperCandidate('12345678AB');
    $candidate->update(['status' => 'pending_admission']);

    $response = $this
        ->actingAs($candidate, 'candidate')
        ->get(route('candidate.dashboard'));

    $response->assertOk();
    $response->assertSee('Your admission is being processed by JAMB CAPS.');
});

test('dashboard shows admission letter and acceptance fee actions when admitted', function () {
    $candidate = makeStepperCandidate('12345678AB');
    $candidate->update(['status' => 'admitted']);

    $response = $this
        ->actingAs($candidate, 'candidate')
        ->get(route('candidate.dashboard'));

    $response->assertOk();
    $response->assertSee('Congratulations! You have been admitted.');
    $response->assertSee('Download Admission Letter');
    $response->assertSee('Pay Acceptance Fee');
    $response->assertSee(route('candidate.admission.letter.download'), false);
    $response->assertSee(route('candidate.payment.acceptance'), false);
});

test('dashboard keeps admission letter access after acceptance fee is confirmed', function () {
    $candidate = makeStepperCandidate('12345678AB');
    $candidate->update(['status' => 'acceptance_confirmed']);

    $response = $this
        ->actingAs($candidate, 'candidate')
        ->get(route('candidate.dashboard'));

    $response->assertOk();
    $response->assertSee('Acceptance fee paid ✓');
    $response->assertSee('Download Admission Letter');
    $response->assertSee(route('candidate.admission.letter.download'), false);
    $response->assertSee('Activate Student Account');
    $response->assertSee(route('candidate.convert.student'), false);
    $response->assertDontSee('Pay Acceptance Fee');
});

test('dashboard welcomes enrolled students with their student details', function () {
    $faculty = Faculty::create([
        'name' => 'Faculty of Public Relations',
        'code' => 'FPR',
    ]);

    $department = Department::create([
        'faculty_id' => $faculty->id,
        'name' => 'Department of Mass Communication',
        'code' => 'MAS',
    ]);

    $candidate = makeStepperCandidate('12345678AB');
    $candidate->update(['status' => 'student']);

    Student::create([
        'candidate_id' => $candidate->id,
        'faculty_id' => $faculty->id,
        'department_id' => $department->id,
        'matric_number' => 'UPR/2026/0001',
        'student_email' => 'john.doe@upr.edu.ng',
        'password' => 'secret',
    ]);

    $response = $this
        ->actingAs($candidate, 'candidate')
        ->get(route('candidate.dashboard'));

    $response->assertOk();
    $response->assertSee('You are now an enrolled student of UPR!');
    $response->assertSee('UPR/2026/0001');
    $response->assertSee('john.doe@upr.edu.ng');
    $response->assertSee('Faculty of Public Relations');
    $response->assertSee('Department of Mass Communication');
    $response->assertDontSee('Save Your Login Credentials Now');
});

test('dashboard flashes generated student credentials once after conversion', function () {
    $faculty = Faculty::create([
        'name' => 'Faculty of Public Relations',
        'code' => 'FPR',
    ]);

    $department = Department::create([
        'faculty_id' => $faculty->id,
        'name' => 'Department of Mass Communication',
        'code' => 'MAS',
    ]);

    $candidate = makeStepperCandidate('12345678AB');
    $candidate->update(['status' => 'student']);

    Student::create([
        'candidate_id' => $candidate->id,
        'faculty_id' => $faculty->id,
        'department_id' => $department->id,
        'matric_number' => 'UPR/2026/0001',
        'student_email' => 'john.doe@upr.edu.ng',
        'password' => 'secret',
    ]);

    $response = $this
        ->withSession(['generated_password' => 'Temp@123'])
        ->actingAs($candidate, 'candidate')
        ->get(route('candidate.dashboard'));

    $response->assertOk();
    $response->assertSee('Save Your Login Credentials Now');
    $response->assertSee('Student Email: john.doe@upr.edu.ng');
    $response->assertSee('Password: Temp@123');
    $response->assertSee('This password will not be shown again. Please save it securely.');
});
