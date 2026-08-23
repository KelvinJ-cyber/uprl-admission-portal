<?php

use App\Models\Candidate;
use App\Models\Payment;

function makeCandidate(string $jamb): Candidate
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

function makePayment(Candidate $candidate, string $reference, string $invoice): Payment
{
    return Payment::create([
        'candidate_id' => $candidate->id,
        'invoice_number' => $invoice,
        'type' => 'screening',
        'amount' => 2000,
        'reference' => $reference,
        'status' => 'success',
    ]);
}

test('candidate can view their own payment receipt', function () {
    $candidate = makeCandidate('12345678AB');
    $payment = makePayment($candidate, 'REF-OWNER', 'INV-OWNER');

    $response = $this
        ->actingAs($candidate, 'candidate')
        ->get(route('candidate.payment.receipt', $payment));

    $response->assertOk();
    $response->assertSee('INV-OWNER');
    $response->assertSee('REF-OWNER');
});

test('candidate cannot view another candidates payment receipt', function () {
    $owner = makeCandidate('12345678AB');
    $payment = makePayment($owner, 'REF-OWNER', 'INV-OWNER');

    $intruder = makeCandidate('87654321CD');

    $response = $this
        ->actingAs($intruder, 'candidate')
        ->get(route('candidate.payment.receipt', $payment));

    $response->assertForbidden();
});

test('dashboard shows a receipt link after a successful screening payment', function () {
    $candidate = makeCandidate('12345678AB');
    $candidate->update(['status' => 'payment_confirmed']);
    $payment = makePayment($candidate, 'REF-OWNER', 'INV-OWNER');

    $response = $this
        ->actingAs($candidate, 'candidate')
        ->get(route('candidate.dashboard'));

    $response->assertOk();
    $response->assertSee('View Receipt');
    $response->assertSee(route('candidate.payment.receipt', $payment));
});
