<?php

use Livewire\Volt\Component;

new class extends Component {}; ?>


<div>
    <x-modal name="confirm-delete" focusable>
        <div class="p-6">
            <h2 class="text-2xl font-medium">Confirmar Exclusão</h2>
            <p class="mt-1 text-base">Todos os dados serão permanentemente apagados. Tem certeza que deseja apagar?</p>
            <div class="flex items-center gap-4 pt-8 mt-4">
                <x-danger-button wire:click="$parent.delete()" x-on:click="$dispatch('close')">EXCLUIR</x-danger-button>
                <x-secondary-button x-on:click.prevent="$dispatch('close')">Cancelar</x-secondary-button>
            </div>
        </div>
    </x-modal>
</div>
