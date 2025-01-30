@props(['active'])

@php
    $classes = 'flex items-center p-3 font-semibold transition-all duration-200 hover:bg-gray-100';
@endphp

<a {{ $attributes->class(['text-lg underline' => $active ?? false])->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
