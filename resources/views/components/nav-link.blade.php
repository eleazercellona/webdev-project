@props(['active'])

@php
$classes = ($active ?? false)
    ? 'inline-flex items-center px-4 rounded-md bg-gray-100 primary_color font-semibold'
    : 'inline-flex items-center px-4 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-50';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
