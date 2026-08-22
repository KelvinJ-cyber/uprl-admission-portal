<x-app-layout>
    <div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <div class="mb-6 text-center">
                <h1 class="text-2xl font-bold text-gray-900">Candidate Login</h1>
                <p class="mt-2 text-sm text-gray-600">Enter your JAMB Registration Number to continue</p>
            </div>

            <form method="POST" action="{{ route('candidate.login.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="jamb_reg_number" class="block text-sm font-medium text-gray-700 mb-1">
                        JAMB Registration Number
                    </label>
                    <input
                        type="text"
                        name="jamb_reg_number"
                        id="jamb_reg_number"
                        value="{{ old('jamb_reg_number') }}"
                        placeholder="e.g. 2026JAMB00123"
                        autofocus
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    @error('jamb_reg_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full rounded-md bg-indigo-600 px-4 py-2 font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Continue
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
