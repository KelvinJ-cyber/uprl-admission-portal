<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Welcome, {{ auth('candidate')->user()->first_name }} {{ auth('candidate')->user()->surname }}
            </h2>

            @if (auth('candidate')->user()->status === 'screening_passed' || auth('candidate')->user()->status === 'screening_pending')
                <a
                    href="{{ route('candidate.screening.report.download') }}"
                    class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Download Screening Report
                </a>
            @endif
        </div>
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

        $statusOrder = [
            'eligible_for_screening' => 1,
            'payment_confirmed' => 2,
            'screening_passed' => 3,
            'screening_pending' => 3,
            'successfully_screened' => 4,
            'recommended_for_admission' => 5,
            'pending_admission' => 6,
            'admitted' => 7,
            'acceptance_confirmed' => 8,
            'student' => 9,
        ];
        $currentOrder = $statusOrder[$candidate->status] ?? 0;

        $student = $candidate->student;

        $steps = [
            ['order' => 1, 'label' => 'Screening', 'title' => 'Eligible for Screening'],
            ['order' => 2, 'label' => 'Payment', 'title' => 'Payment Confirmed'],
            ['order' => 3, 'label' => 'Screening Result', 'title' => 'Screening Completed'],
            ['order' => 4, 'label' => 'Documents Verified', 'title' => 'Successfully Screened'],
            ['order' => 5, 'label' => 'Recommended', 'title' => 'Recommended for Admission'],
            ['order' => 6, 'label' => 'Sent to JAMB', 'title' => 'Pending Admission'],
            ['order' => 7, 'label' => 'Admitted', 'title' => 'Admitted'],
        ];
    @endphp

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 rounded-lg bg-white p-6 shadow-sm">
                <div class="overflow-x-auto">
                    <ol class="flex items-start" style="min-width: 780px;">
                        @foreach ($steps as $index => $step)
                            @php
                                $isCompleted = $currentOrder > $step['order'];
                                $isCurrent = $currentOrder === $step['order'];
                                $leftFilled = $currentOrder >= $step['order'];
                                $rightFilled = $currentOrder > $step['order'];
                                $isCelebratory = $candidate->status === 'admitted' && $step['order'] === 7;
                            @endphp
                            <li class="flex flex-1 flex-col items-center">
                                <div class="flex w-full items-center">
                                    <div class="h-0.5 flex-1 {{ $index === 0 ? 'invisible' : ($leftFilled ? 'bg-indigo-600' : 'bg-gray-200') }}"></div>

                                    @if ($isCelebratory)
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-white">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </div>
                                    @elseif ($isCompleted)
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-white">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </div>
                                    @elseif ($isCurrent)
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border-2 border-indigo-600 bg-white text-sm font-semibold text-indigo-600 ring-4 ring-indigo-100">
                                            {{ $step['order'] }}
                                        </div>
                                    @else
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border-2 border-gray-300 bg-white text-sm font-semibold text-gray-400">
                                            {{ $step['order'] }}
                                        </div>
                                    @endif

                                    <div class="h-0.5 flex-1 {{ $index === count($steps) - 1 ? 'invisible' : ($rightFilled ? 'bg-indigo-600' : 'bg-gray-200') }}"></div>
                                </div>

                                <span class="mt-2 px-1 text-center text-xs {{ $isCelebratory ? 'font-bold text-emerald-700' : ($isCurrent ? 'font-bold text-indigo-700' : ($isCompleted ? 'font-medium text-gray-700' : 'text-gray-400')) }}" title="{{ $step['title'] }}">
                                    {{ $step['label'] }}
                                </span>

                                @if ($step['order'] === 3 && $candidate->status === 'screening_pending')
                                    <p class="mt-1 px-1 text-center text-xs text-red-600">
                                        Screening incomplete — see dashboard for details
                                    </p>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>

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

            @if ($candidate->status === 'pending_admission')
                <div class="mb-4 rounded-md bg-gray-100 p-3 text-gray-700">
                    Your admission is being processed by JAMB CAPS.
                </div>
            @endif

            @if ($candidate->status === 'admitted')
                <div class="mb-6 rounded-lg bg-green-600 p-8 text-center text-white">
                    <p class="text-2xl font-bold">Congratulations! You have been admitted.</p>
                </div>

                <div class="mb-6 flex flex-col items-center gap-3">
                    <a
                        href="{{ route('candidate.admission.letter.download') }}"
                        class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Download Admission Letter
                    </a>
                    <a
                        href="{{ route('candidate.payment.acceptance') }}"
                        class="inline-flex items-center gap-2 rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                    >
                        Pay Acceptance Fee
                    </a>
                </div>
            @endif

            @if ($candidate->status === 'acceptance_confirmed')
                <div class="mb-4 rounded-md bg-green-100 p-3 text-green-800">
                    Acceptance fee paid ✓
                </div>

                <div class="mb-4">
                    <a
                        href="{{ route('candidate.admission.letter.download') }}"
                        class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Download Admission Letter
                    </a>
                </div>

                <div class="mb-6">
                    <form method="POST" action="{{ route('candidate.convert.student') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Activate Student Account
                        </button>
                    </form>
                </div>
            @endif

            @if ($candidate->status === 'student')
                <div class="mb-6 rounded-lg bg-green-600 p-8 text-center text-white">
                    <p class="text-2xl font-bold">You are now an enrolled student of UPR!</p>
                </div>

                @if (session('generated_password'))
                    <div class="mb-6 rounded-lg border-4 border-amber-400 bg-amber-100 p-6">
                        <h3 class="text-lg font-bold text-amber-900">Save Your Login Credentials Now</h3>
                        <p class="mt-2 text-sm text-amber-800">Student Email: {{ $student->student_email }}</p>
                        <p class="text-sm text-amber-800">Password: {{ session('generated_password') }}</p>
                        <p class="mt-3 text-sm font-semibold text-amber-900">This password will not be shown again. Please save it securely.</p>
                    </div>
                @endif

                <div class="mb-6 overflow-hidden rounded-lg bg-white shadow-sm">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Your Student Details</h3>

                        <dl class="divide-y divide-gray-200">
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="text-sm text-gray-500">Matric Number</dt>
                                <dd class="text-sm font-medium text-gray-900 text-right">{{ $student->matric_number }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="text-sm text-gray-500">Student Email</dt>
                                <dd class="text-sm font-medium text-gray-900 text-right">{{ $student->student_email }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="text-sm text-gray-500">Faculty</dt>
                                <dd class="text-sm font-medium text-gray-900 text-right">{{ $student->faculty->name ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="text-sm text-gray-500">Department</dt>
                                <dd class="text-sm font-medium text-gray-900 text-right">{{ $student->department->name ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
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
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
