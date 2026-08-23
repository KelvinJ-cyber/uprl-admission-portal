<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Verify Your O'Level Result
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900 space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Submitted Details</h3>

                        <dl class="divide-y divide-gray-200">
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="text-sm text-gray-500">Examination Type</dt>
                                <dd class="text-sm font-medium text-gray-900 text-right">{{ $olevelResult->exam_type }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="text-sm text-gray-500">Examination Year</dt>
                                <dd class="text-sm font-medium text-gray-900 text-right">{{ $olevelResult->exam_year }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="text-sm text-gray-500">Examination Number</dt>
                                <dd class="text-sm font-medium text-gray-900 text-right">{{ $olevelResult->exam_number }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 py-3">
                                <dt class="text-sm text-gray-500">Scratch Card / Token</dt>
                                <dd class="text-sm font-medium text-gray-900 text-right">{{ $olevelResult->scratch_card_or_token }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold mb-4">Subjects</h3>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="py-2 text-left text-sm font-medium text-gray-500">Subject</th>
                                    <th class="py-2 text-right text-sm font-medium text-gray-500">Grade</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($olevelResult->subjectGrades as $subjectGrade)
                                    <tr>
                                        <td class="py-2 text-sm text-gray-900">{{ $subjectGrade->subject_name }}</td>
                                        <td class="py-2 text-sm font-medium text-gray-900 text-right">{{ $subjectGrade->grade }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="rounded-md bg-yellow-100 p-4 text-sm text-yellow-800">
                        Click below to verify your result with the examination body.
                    </div>

                    <form method="POST" action="{{ route('candidate.olevel.verify.store') }}">
                        @csrf
                        <button
                            type="submit"
                            class="w-full rounded-md bg-green-600 px-4 py-2 font-semibold text-white transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >
                            Verify Result
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
