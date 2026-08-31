<x-app-layout>
    <div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-bold text-gray-900">Student Login</h1>
                <p class="mt-2 text-sm text-gray-600">Sign in with your student email and password</p>
            </div>

            <form method="POST" action="{{ route('student.login.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="student_email" class="block text-sm font-medium text-gray-700 mb-1">
                        Student Email
                    </label>
                    <input
                        type="email"
                        name="student_email"
                        id="student_email"
                        value="{{ old('student_email') }}"
                        placeholder="e.g. john.doe@upr.edu.ng"
                        autofocus
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('student_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Enter your password"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full rounded-md bg-indigo-600 px-4 py-2 font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Login
                </button>
            </form>
        </div>
    </div>
</x-app-layout>