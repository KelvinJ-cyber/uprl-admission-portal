<x-app-layout>
    <div class="p-6 max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Import Candidates from JAMB</h1>

        @if (session('status'))
            @php $failuresCount = session('failures') ? count(session('failures')) : 0; @endphp
            <div class="{{ $failuresCount > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }} p-3 rounded mb-4">
                {{ session('status') }}
                @if ($failuresCount > 0)
                    <p class="mt-1">{{ $failuresCount }} row(s) failed validation.</p>
                @endif
            </div>
        @endif

        @if (session('failures') && count(session('failures')) > 0)
            <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
                <p class="font-semibold mb-2">Failed Rows:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach (session('failures') as $failure)
                        <li>
                            Row {{ $failure['row'] }}:
                            {{ implode(', ', $failure['errors']) }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @error('file')
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            {{ $message }}
        </div>
        @enderror

        <form method="POST" action="{{ route('staff.candidates.import.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" accept=".csv,.xlsx,.xls" class="mb-4 block">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">
                Upload & Import
            </button>
        </form>
    </div>
</x-app-layout>
