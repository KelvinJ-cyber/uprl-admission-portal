<?php

use App\Models\Candidate;
use App\Models\Staff;

function makeStaff(): Staff
{
    return Staff::create([
        'name' => 'Admission Officer',
        'email' => fake()->unique()->safeEmail(),
        'password' => 'password',
    ]);
}

function makeCandidateWithStatus(string $jamb, string $status): Candidate
{
    $candidate = Candidate::create([
        'jamb_reg_number' => $jamb,
        'surname' => 'Doe',
        'first_name' => 'John',
        'gender' => 'male',
        'date_of_birth' => '2000-01-01',
        'state_of_origin' => 'Lagos',
        'local_government' => 'Ikeja',
        'utme_score' => 250,
        'status' => $status,
    ]);

    return $candidate;
}

test('admissions index shows the export to CAPS button and its form', function () {
    makeCandidateWithStatus('1000000001AB', 'recommended_for_admission');

    $response = $this
        ->actingAs(makeStaff())
        ->get(route('staff.admissions.index'));

    $response->assertOk();
    $response->assertSee('Export Recommended Candidates to JAMB CAPS');
    $response->assertSee(route('staff.admissions.export.caps'), false);
});

test('admissions index renders pending admission status badge and mark admitted action', function () {
    makeCandidateWithStatus('1000000002AB', 'pending_admission');

    $response = $this
        ->actingAs(makeStaff())
        ->get(route('staff.admissions.index'));

    $response->assertOk();
    $response->assertSee('Pending Admission');
    $response->assertSee('Mark as Admitted');
    $response->assertSee('/staff/admissions/'.Candidate::first()->id.'/mark-admitted', false);
});

test('admissions index renders admitted status badge and admitted text', function () {
    makeCandidateWithStatus('1000000003AB', 'admitted');

    $response = $this
        ->actingAs(makeStaff())
        ->get(route('staff.admissions.index'));

    $response->assertOk();
    $response->assertSee('Admitted');
});

test('admissions index shows the existing recommend action for screening passed candidates', function () {
    makeCandidateWithStatus('1000000004AB', 'screening_passed');

    $response = $this
        ->actingAs(makeStaff())
        ->get(route('staff.admissions.index'));

    $response->assertOk();
    $response->assertSee('Recommend for Admission');
});

test('admissions index shows recommended text for recommended candidates', function () {
    makeCandidateWithStatus('1000000005AB', 'recommended_for_admission');

    $response = $this
        ->actingAs(makeStaff())
        ->get(route('staff.admissions.index'));

    $response->assertOk();
    $response->assertSee('Recommended');
});
