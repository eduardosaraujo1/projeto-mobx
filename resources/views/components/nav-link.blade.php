@props(['active'])

@php
    $classes = 'flex items-center p-3 transition-all duration-200 hover:bg-gray-100';
@endphp

<a {{ $attributes->class(['font-bold' => $active ?? false])->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
