@php
    $imobiliaria = current_imobiliaria();

    if (isset($imobiliaria)) {
        redirect()->route('imobiliaria.index');
    }
@endphp
<x-app-layout>
    <x-slot name="heading">
        Nenhuma imobiliária encontrada
    </x-slot>
    <x-alert
        title="Sua conta não está associada a nenhuma imobiliária. Contate seu administrador para resolver o problema."
        negative />
</x-app-layout>
