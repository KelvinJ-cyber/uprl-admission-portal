<?php

use App\Models\Candidate;

test('olevel entry page renders for a candidate without a result', function () {
    $candidate = Candidate::create([
        'jamb_reg_number' => '12345678AB',
        'surname' => 'Doe',
        'first_name' => 'John',
        'gender' => 'male',
        'date_of_birth' => '2000-01-01',
        'state_of_origin' => 'Lagos',
        'local_government' => 'Ikeja',
        'utme_score' => 250,
    ]);

    $response = $this
        ->actingAs($candidate, 'candidate')
        ->get(route('candidate.olevel.create'));

    $response->assertOk();
    $response->assertSee("Enter Your O'Level Result", false);
    $response->assertSee(route('candidate.olevel.store'), false);
    $response->assertSee('name="exam_type"', false);
});
