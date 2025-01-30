<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="w-12 min-h-screen px-2 py-2 space-y-4 bg-white shadow-lg">
    <livewire:profile.avatar-dropdown />
    <x-mini-button rounded icon="bell" flat interaction:solid black />
</div>
