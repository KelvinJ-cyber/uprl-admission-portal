<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Welcome, {{ auth('student')->user()->candidate->first_name }} {{ auth('student')->user()->candidate->surname }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Your Student Details</h3>

                    <dl class="divide-y divide-gray-200">
                        <div class="flex items-center justify-between gap-4 py-3">
                            <dt class="text-sm text-gray-500">Matric Number</dt>
                            <dd class="text-sm font-medium text-gray-900 text-right">{{ auth('student')->user()->matric_number }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 py-3">
                            <dt class="text-sm text-gray-500">Student Email</dt>
                            <dd class="text-sm font-medium text-gray-900 text-right">{{ auth('student')->user()->student_email }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 py-3">
                            <dt class="text-sm text-gray-500">Faculty</dt>
                            <dd class="text-sm font-medium text-gray-900 text-right">{{ auth('student')->user()->faculty->name ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 py-3">
                            <dt class="text-sm text-gray-500">Department</dt>
                            <dd class="text-sm font-medium text-gray-900 text-right">{{ auth('student')->user()->department->name ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>