<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaystackService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

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
    // Initialize the payment process for screening fee
    public function screeningPay(Request $request)
    {
        $candidate = auth('candidate')->user();

        $screeningFee = 2000;

        $result = $this->paystack->initializePayment(
            email: $candidate->email ?? 'test@upr.edu.ng',
            amount: $screeningFee,
            callback_url: route('candidate.payment.callback')
        );

        $invoiceNumber = 'INV-'.now()->format('Ymd').'-'.str_pad($candidate->id, 5, '0', STR_PAD_LEFT).'-'.strtoupper(Str::random(4));

        Payment::create([
            'candidate_id' => $candidate->id,
            'invoice_number' => $invoiceNumber,
            'type' => 'screening',
            'amount' => $screeningFee,
            'reference' => $result['reference'],
            'status' => 'pending',
        ]);

        return redirect($result['authorization_url']); // redirect to Paystack payment page

    }

    /**
     * @throws ConnectionException
     */

    // Handle the callback from Paystack after payment
    public function callback(Request $request)
    {
        $reference = $request->query('reference');

        $verification = $this->paystack->verifyPayment($reference);

        $payment = Payment::where('reference', $reference)->firstOrFail();

        if ($verification['data']['status'] === 'success') {
            $payment->update(['status' => 'success']);

            if ($payment->type === 'screening') {
                $payment->candidate->update(['status' => 'payment_confirmed']);
                $message = 'Payment successful! You may proceed to screening.';
            } elseif ($payment->type === 'acceptance') {
                $payment->candidate->update(['status' => 'acceptance_confirmed']);
                $message = 'Acceptance fee paid successfully! Welcome to UPR.';
            }

            return redirect()->route('candidate.dashboard')->with('success', $message);
        }

        $payment->update(['status' => 'failed']);

        return redirect()->route('candidate.dashboard')->with('error', 'Payment could not be verified. Please try again.');
    }

    public function receipt(Payment $payment): View
    {
        abort_if($payment->candidate_id !== auth('candidate')->id(), 403);

        return view('candidate.payment.receipt', ['payment' => $payment]);
    }

    /**
     * @throws ConnectionException
     */
    public function acceptancePay(Request $request)
    {
        $candidate = auth('candidate')->user();

        if ($candidate->status !== 'admitted') {
            return back()->with('error', 'Acceptance fee is only payable once you have been admitted.');
        }

        $acceptanceFee = 5000;

        $result = $this->paystack->initializePayment(
            email: $candidate->email ?? 'test@upr.edu.ng',
            amount: $acceptanceFee,
            callback_url: route('candidate.payment.callback')
        );
        $invoiceNumber = 'INV-'.now()->format('Ymd').'-'.str_pad($candidate->id, 5, '0', STR_PAD_LEFT).'-'.strtoupper(Str::random(4));

        Payment::create([
            'candidate_id' => $candidate->id,
            'invoice_number' => $invoiceNumber,
            'type' => 'acceptance',
            'amount' => $acceptanceFee,
            'reference' => $result['reference'],
            'status' => 'pending',
        ]);

        return redirect($result['authorization_url']);

    }
}
