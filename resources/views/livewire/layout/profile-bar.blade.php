<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="sticky top-0 h-screen px-2 py-2 space-y-4 bg-white shadow-lg w-min">
    <livewire:settings.avatar-dropdown />
    <x-mini-button icon="bell" lg rounded black flat interaction:solid>
        {{-- <x-icon name="bell" xl class="w-4 h-4 fill-white" /> --}}
    </x-mini-button>
</div>
