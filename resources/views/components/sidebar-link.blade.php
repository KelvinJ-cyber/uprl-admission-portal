@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium bg-indigo-50 text-indigo-700'
            : 'flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
