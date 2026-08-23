<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Payment Receipt
        </h2>
    </x-slot>

    @php
        $rows = [
            'Invoice Number' => $payment->invoice_number,
            'Transaction ID' => $payment->reference,
            'Candidate' => $payment->candidate->first_name . ' ' . $payment->candidate->surname,
            'Payment Type' => ucfirst($payment->type),
            'Amount' => 'N' . number_format($payment->amount, 2),
            'Status' => ucfirst($payment->status),
            'Date' => $payment->created_at->format('F j, Y g:i A'),
        ];
    @endphp

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="p-8 text-gray-900">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold tracking-tight">Payment Receipt</h3>
                        <p class="mt-1 text-sm text-gray-500">{{ config('app.name') }}</p>
                    </div>

                    <div class="my-6 border-t border-dashed border-gray-300"></div>

                    <dl class="divide-y divide-gray-100">
                        @foreach ($rows as $label => $value)
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="text-sm text-gray-500">{{ $label }}</dt>
                                <dd class="text-sm font-medium text-gray-900 text-right">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    <div class="my-6 border-t border-dashed border-gray-300"></div>

                    <div class="flex justify-center print:hidden">
                        <button
                            type="button"
                            onclick="window.print()"
                            class="inline-block rounded-md bg-indigo-600 px-4 py-2 font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Print Receipt
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
