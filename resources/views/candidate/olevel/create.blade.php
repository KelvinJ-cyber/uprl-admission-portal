<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Enter Your O'Level Result
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-100 p-3 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('candidate.olevel.store') }}" class="space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="exam_type" class="block text-sm font-medium text-gray-700">Examination Type</label>
                                <select
                                    id="exam_type"
                                    name="exam_type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="">Select type</option>
                                    @foreach (['WAEC', 'NECO', 'NABTEB'] as $type)
                                        <option value="{{ $type }}" @selected(old('exam_type') === $type)>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('exam_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="exam_year" class="block text-sm font-medium text-gray-700">Examination Year</label>
                                <input
                                    type="number"
                                    id="exam_year"
                                    name="exam_year"
                                    value="{{ old('exam_year') }}"
                                    placeholder="e.g. 2024"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                @error('exam_year')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="exam_number" class="block text-sm font-medium text-gray-700">Examination Number</label>
                                <input
                                    type="text"
                                    id="exam_number"
                                    name="exam_number"
                                    value="{{ old('exam_number') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                @error('exam_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="scratch_card_or_token" class="block text-sm font-medium text-gray-700">Scratch Card Number / Token</label>
                                <input
                                    type="text"
                                    id="scratch_card_or_token"
                                    name="scratch_card_or_token"
                                    value="{{ old('scratch_card_or_token') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                @error('scratch_card_or_token')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div x-data="{ subjects: @js(old('subjects', [['subject_name' => '', 'grade' => '']])) }" class="space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold">Subjects</h3>
                                <p class="text-sm text-gray-500">Enter at least 5 subjects with grades</p>
                            </div>

                            <template x-for="(subject, index) in subjects" :key="index">
                                <div class="flex items-end gap-3">
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700">Subject</label>
                                        <input
                                            type="text"
                                            x-model="subject.subject_name"
                                            :name="`subjects[${index}][subject_name]`"
                                            placeholder="e.g. Mathematics"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </div>

                                    <div class="w-32">
                                        <label class="block text-sm font-medium text-gray-700">Grade</label>
                                        <select
                                            x-model="subject.grade"
                                            :name="`subjects[${index}][grade]`"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                            <option value="">--</option>
                                            @foreach (['A1', 'B2', 'B3', 'C4', 'C5', 'C6', 'D7', 'E8', 'F9'] as $grade)
                                                <option value="{{ $grade }}">{{ $grade }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button
                                        type="button"
                                        x-show="subjects.length > 1"
                                        @click="subjects.splice(index, 1)"
                                        class="mb-2 text-sm font-medium text-red-600 hover:text-red-800"
                                    >
                                        Remove
                                    </button>
                                </div>
                            </template>

                            <button
                                type="button"
                                @click="subjects.push({ subject_name: '', grade: '' })"
                                class="inline-flex items-center rounded-md border border-indigo-600 px-3 py-2 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                + Add Subject
                            </button>

                            @error('subjects')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <button
                                type="submit"
                                class="w-full rounded-md bg-indigo-600 px-4 py-2 font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Submit O'Level Details
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
