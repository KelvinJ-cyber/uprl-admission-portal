<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaystackService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected PaystackService $paystack;

    public function __construct(PaystackService $paystack)
    {
        $this->paystack = $paystack;
    }

    /**
     * @throws ConnectionException
     */
    public function screeningPay(Request $request)
    {
        $candidate = auth("candidate")->user();

        $screeningFee = 2000;

        $result = $this->paystack->initializePayment(
            email: $candidate->email ?? 'test@upr.edu.ng',
            amount: $screeningFee,
            callback_url: route('candidate.payment.callback')
        );

        Payment::create([
            'candidate_id' => $candidate->id,
            'type' => 'screening',
            'amount' => $screeningFee,
            'reference' => $result['reference'],
            'status' => 'pending',
        ]);

        return redirect($result['authorization_url']);

    }

    /**
     * @throws ConnectionException
     */
    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        $verification = $this->paystack->verifyPayment($reference);

        $payment = Payment::where('reference', $reference)->firstOrFail();

        if ($verification['data']['status'] === 'success') {
            $payment->update(['status' => 'success']);
            $payment->candidate->update(['status' => 'payment_confirmed']);

            return redirect()->route('candidate.dashboard')->with('success', 'Payment successful! You may proceed to screening.');
        }

        $payment->update(['status' => 'failed']);

        return redirect()->route('candidate.dashboard')->with('error', 'Payment could not be verified. Please try again.');
    }
}
