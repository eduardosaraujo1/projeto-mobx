@props([
    "href",
])
@php
    $class =
        "inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50";
@endphp

@isset($href)
    <a {{ $attributes->merge(["href" => $href, "class" => $class]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(["type" => "submit", "class" => $class]) }}>
        {{ $slot }}
    </button>
@endisset
