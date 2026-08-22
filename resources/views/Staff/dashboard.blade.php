<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold">Staff Dashboard</h1>
        <p>Welcome, {{ auth('web')->user()->name }}.</p>
    </div>
</x-app-layout>
