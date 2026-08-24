<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Eligibility Result
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <p class="text-center text-lg font-medium text-gray-700">
                        {{ $candidate->first_name }} {{ $candidate->surname }}  {{ $course->name }}
                    </p>

                    @if ($result['eligible'])
                        <div class="rounded-lg bg-green-600 p-8 text-center text-white">
                            <div class="text-5xl leading-none"></div>
                            <p class="mt-3 text-2xl font-bold">Eligible for {{ $course->name }}</p>
                        </div>
                    @else
                        <div class="rounded-lg bg-red-600 p-8 text-center text-white">
                            <div class="text-5xl leading-none"></div>
                            <p class="mt-3 text-2xl font-bold">Not Eligible for {{ $course->name }}</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-2">Reasons:</h3>
                            <ul class="list-disc list-inside space-y-1 text-sm text-gray-700">
                                @foreach ($result['reasons'] as $reason)
                                    <li>{{ $reason }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="border-t border-gray-200 pt-6 text-center">
                        <a
                            href="{{ route('eligibility.check') }}"
                            class="inline-block rounded-md bg-indigo-600 px-4 py-2 font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Check Another Course
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
