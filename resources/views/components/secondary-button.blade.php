@props([
    "href",
])
@php
    $class =
        "inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-gray-700 uppercase transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25";
@endphp

@isset($href)
    <a {{ $attributes->merge(["href" => $href, "class" => $class]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(["type" => "button", "class" => $class]) }}>
        {{ $slot }}
    </button>
@endisset
