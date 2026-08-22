<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Welcome, {{ auth('candidate')->user()->first_name }} {{ auth('candidate')->user()->surname }}
        </h2>
    </x-slot>

    @php
        $candidate = auth('candidate')->user();
        $details = [
            'JAMB Registration Number' => $candidate->jamb_reg_number,
            'Surname' => $candidate->surname,
            'First Name' => $candidate->first_name,
            'Other Names' => $candidate->other_names,
            'Gender' => $candidate->gender,
            'Date of Birth' => $candidate->date_of_birth,
            'State of Origin' => $candidate->state_of_origin,
            'Local Government' => $candidate->local_government,
            'UTME Score' => $candidate->utme_score,
            'Course applied for' => $candidate->course?->name ?? 'Not yet assigned',
            'Current Status' => $candidate->status,
        ];
    @endphp

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-100 p-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-100 p-3 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Your JAMB Details</h3>

                    <dl class="divide-y divide-gray-200">
                        @foreach ($details as $label => $value)
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="text-sm text-gray-500">{{ $label }}</dt>
                                <dd class="text-sm font-medium text-gray-900 text-right">{{ $value ?? '' }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    <div class="mt-6 border-t border-gray-200 pt-6">
                        @if ($candidate->status === 'eligible_for_screening')
                            <a
                                href="{{ route('candidate.payment.screening') }}"
                                class="inline-block rounded-md bg-indigo-600 px-4 py-2 font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Pay Screening Fee
                            </a>
                        @else
                            <p class="font-semibold text-green-600">Screening fee paid ✓</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <form method="POST" action="{{ route('candidate.logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="rounded-md bg-gray-800 px-4 py-2 font-semibold text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    >
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
