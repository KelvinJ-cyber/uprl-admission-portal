@php
    $webUser = auth('web')->user();
    $candidateUser = auth('candidate')->user();
    $isCandidate = $candidateUser !== null && $webUser === null;
    $currentUser = $webUser ?? $candidateUser;
    $displayName = $webUser?->name
        ?? trim(($candidateUser?->first_name ?? '').' '.($candidateUser?->surname ?? ''));
    $dashboardRoute = $isCandidate ? route('candidate.dashboard') : route('staff.dashboard');
    $dashboardActive = $isCandidate ? request()->routeIs('candidate.dashboard') : request()->routeIs('staff.dashboard');
    $logoutRoute = $isCandidate ? route('candidate.logout') : route('logout');

    $screeningPayment = $isCandidate
        ? $candidateUser->payments()
            ->where('type', 'screening')
            ->where('status', 'success')
            ->latest()
            ->first()
        : null;
@endphp

<!-- Mobile backdrop -->
<div
    x-show="sidebarOpen"
    x-transition.opacity
    @click="sidebarOpen = false"
    class="fixed inset-0 z-30 bg-gray-900/50 sm:hidden"
    x-cloak
></div>

<aside
    x-cloak
    class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-gray-200 bg-white transition-transform duration-200 ease-in-out sm:static sm:z-auto sm:translate-x-0"
    x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <div class="flex h-16 shrink-0 items-center justify-between border-b border-gray-200 px-6">
        <a href="{{ $currentUser ? $dashboardRoute : url('/') }}" class="text-lg font-semibold text-gray-800">
            {{ config('app.name') }}
        </a>

        <button @click="sidebarOpen = false" class="text-gray-400 hover:text-gray-600 sm:hidden">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    @if ($currentUser)
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
            <x-sidebar-link :href="$dashboardRoute" :active="$dashboardActive">
                {{ __('Dashboard') }}
            </x-sidebar-link>

            @if ($isCandidate)
                <x-sidebar-link :href="route('candidate.documents.index')" :active="request()->routeIs('candidate.documents.*')">
                    {{ __('Documents') }}
                </x-sidebar-link>

                @if ($candidateUser->status === 'eligible_for_screening')
                    <x-sidebar-link :href="route('candidate.payment.screening')">
                        {{ __('Pay Screening Fee') }}
                    </x-sidebar-link>
                @endif

                @if ($screeningPayment)
                    <x-sidebar-link :href="route('candidate.payment.receipt', $screeningPayment)" :active="request()->routeIs('candidate.payment.receipt')">
                        {{ __('View Receipt') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('candidate.olevel.create')" :active="request()->routeIs('candidate.olevel.*')">
                        {{ __("Submit O'Level Result") }}
                    </x-sidebar-link>
                @endif
            @endif

            @if ($webUser)
                <x-sidebar-link :href="route('staff.admissions.index')" :active="request()->routeIs('staff.admissions.*')">
                    {{ __('Admissions') }}
                </x-sidebar-link>

                <x-sidebar-link :href="route('staff.candidate.import')" :active="request()->routeIs('staff.candidate.import')">
                    {{ __('Import Candidates') }}
                </x-sidebar-link>

                <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    {{ __('Profile') }}
                </x-sidebar-link>
            @endif
        </nav>

        <div class="shrink-0 border-t border-gray-200 p-4">
            <div class="mb-3">
                <div class="text-sm font-medium text-gray-800">{{ $displayName }}</div>
                @if ($webUser)
                    <div class="text-xs text-gray-500">{{ $webUser->email }}</div>
                @elseif ($candidateUser?->email)
                    <div class="text-xs text-gray-500">{{ $candidateUser->email }}</div>
                @endif
            </div>

            <form method="POST" action="{{ $logoutRoute }}">
                @csrf
                <button
                    type="submit"
                    class="w-full rounded-md bg-gray-800 px-3 py-2 text-sm font-semibold text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                >
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    @endif
</aside>
