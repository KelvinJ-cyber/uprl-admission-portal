<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admissions Dashboard
        </h2>
    </x-slot>

    @php
        $statuses = [
            'eligible_for_screening',
            'payment_confirmed',
            'screening_passed',
            'screening_pending',
            'recommended_for_admission',
        ];

        $statusStyles = [
            'eligible_for_screening' => 'bg-gray-100 text-gray-800',
            'payment_confirmed' => 'bg-yellow-100 text-yellow-800',
            'screening_passed' => 'bg-green-100 text-green-800',
            'screening_pending' => 'bg-red-100 text-red-800',
            'recommended_for_admission' => 'bg-blue-100 text-blue-800',
        ];
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                    <form method="GET" action="{{ route('staff.admissions.index') }}" class="mb-6 flex flex-wrap items-end gap-4">
                        <div>
                            <label for="course_id" class="block text-xs font-medium text-gray-500 mb-1">Course</label>
                            <select
                                id="course_id"
                                name="course_id"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">-- All Courses --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" @selected(request('course_id') == $course->id)>{{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="state_of_origin" class="block text-xs font-medium text-gray-500 mb-1">State of Origin</label>
                            <input
                                type="text"
                                id="state_of_origin"
                                name="state_of_origin"
                                value="{{ request('state_of_origin') }}"
                                placeholder="Filter by state"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div>
                            <label for="min_utme_score" class="block text-xs font-medium text-gray-500 mb-1">Min UTME Score</label>
                            <input
                                type="number"
                                id="min_utme_score"
                                name="min_utme_score"
                                value="{{ request('min_utme_score') }}"
                                placeholder="Minimum UTME score"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div>
                            <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                            <select
                                id="status"
                                name="status"
                                class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">-- Any Status --</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected(request('status') === $status)>
                                        {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $status)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-3">
                            <button
                                type="submit"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Filter
                            </button>
                            <a href="{{ route('staff.admissions.index') }}" class="text-sm text-gray-600 underline hover:text-gray-900">
                                Clear Filters
                            </a>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Name</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">JAMB Reg Number</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Course</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Faculty</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">State of Origin</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">UTME Score</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-500">Status</th>
                                    <th class="px-4 py-3 text-right font-medium text-gray-500">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($candidates as $candidate)
                                    <tr class="odd:bg-white even:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">{{ $candidate->surname }}, {{ $candidate->first_name }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $candidate->jamb_reg_number }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $candidate->course->name ?? 'Not assigned' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $candidate->course->faculty->name ?? '' }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $candidate->state_of_origin }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $candidate->utme_score }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusStyles[$candidate->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $candidate->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            @if ($candidate->status === 'screening_passed')
                                                <form method="POST" action="{{ route('staff.admissions.recommend', $candidate) }}">
                                                    @csrf
                                                    <button
                                                        type="submit"
                                                        class="rounded-md bg-green-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                                    >
                                                        Recommend for Admission
                                                    </button>
                                                </form>
                                            @elseif ($candidate->status === 'recommended_for_admission')
                                                <span class="font-medium text-green-600">Recommended </span>
                                            @else
                                                <span class="text-gray-400"></span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">No candidates found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $candidates->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
