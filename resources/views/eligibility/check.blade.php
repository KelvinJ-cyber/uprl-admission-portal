<x-app-layout>
    <div class="py-12">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-8 text-gray-900">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold tracking-tight">Check Your Course Eligibility</h2>
                        <p class="mt-2 text-sm text-gray-500">
                            Enter your JAMB Registration Number and select a course to instantly check your eligibility.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('eligibility.check.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="jamb_reg_number" class="block text-sm font-medium text-gray-700">JAMB Registration Number</label>
                            <input
                                type="text"
                                id="jamb_reg_number"
                                name="jamb_reg_number"
                                value="{{ old('jamb_reg_number') }}"
                                placeholder="e.g. 2026JAMB00123"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                            @error('jamb_reg_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="course_id" class="block text-sm font-medium text-gray-700">Select Course</label>
                            <select
                                id="course_id"
                                name="course_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Select a course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->name }}</option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-md bg-indigo-600 px-4 py-2 font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Check Eligibility
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
