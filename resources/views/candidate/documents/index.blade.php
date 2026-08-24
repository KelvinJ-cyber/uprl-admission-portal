<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Upload Required Documents
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="text-sm text-gray-500">
                        Accepted formats: PDF, JPEG. Maximum size: 2MB per file.
                    </p>

                    @if (session('success'))
                        <div class="mt-4 rounded-md bg-green-100 p-3 text-green-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @error('file')
                        <div class="mt-4 rounded-md bg-red-100 p-3 text-red-800">
                            {{ $message }}
                        </div>
                    @enderror

                    @error('document_type')
                        <div class="mt-4 rounded-md bg-red-100 p-3 text-red-800">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="mt-6 space-y-4">
                        @foreach ($documentTypes as $type)
                            <div class="rounded-lg border border-gray-200 p-4">
                                <div class="flex flex-wrap items-center justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-3">
                                            <h3 class="font-semibold text-gray-900">
                                                {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $type)) }}
                                            </h3>

                                            @if ($documents->has($type))
                                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                    Uploaded 
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">
                                                    Not uploaded
                                                </span>
                                            @endif
                                        </div>

                                        @if ($documents->has($type))
                                            <p class="mt-1 text-sm text-gray-500">
                                                {{ $documents[$type]->original_filename }}
                                            </p>
                                        @endif
                                    </div>

                                    <form
                                        method="POST"
                                        action="{{ route('candidate.documents.store') }}"
                                        enctype="multipart/form-data"
                                        class="flex items-end gap-3"
                                    >
                                        @csrf
                                        <input type="hidden" name="document_type" value="{{ $type }}">

                                        <div>
                                            @if ($documents->has($type))
                                                <span class="block text-xs text-gray-500 mb-1">Replace</span>
                                            @endif
                                            <input
                                                type="file"
                                                name="file"
                                                accept=".pdf,.jpeg,.jpg"
                                                class="block w-full text-sm text-gray-700 file:mr-3 file:rounded-md file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100"
                                            >
                                        </div>

                                        <button
                                            type="submit"
                                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            {{ $documents->has($type) ? 'Replace' : 'Upload' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
