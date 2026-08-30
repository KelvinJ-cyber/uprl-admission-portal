<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaystackService
{
    protected string $secret_key;

    public function __construct()
    {
        $this->secret_key = config('services.paystack.secret_key');
    }

    /**
     * @throws ConnectionException
     */
    public function initializePayment(string $email, float $amount, string $callback_url): array
    {

        $reference = 'UPR-'.Str::upper(Str::random(12));

        $response = Http::withToken($this->secret_key)
            ->post('https://api.paystack.co/transaction/initialize', [
                'email' => $email,
                'amount' => $amount * 100, // Paystack expects amount in kobo
                'callback_url' => $callback_url, // The URL to redirect to after payment
                'reference' => $reference,
            ]);

        $data = $response->json();

        return [
            'status' => $data['status'],
            'message' => $data['message'],
            'authorization_url' => $data['data']['authorization_url'] ?? null,
            'reference' => $reference,
        ];

    }

    /**
     * @throws ConnectionException
     */
    public function verifyPayment(string $reference): array
    {
        $response = Http::withToken($this->secret_key)
            ->get("https://api.paystack.co/transaction/verify/{$reference}");

        return $response->json();
    }
}
