<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="w-12 min-h-screen px-2 py-2 space-y-6 bg-white shadow-lg">
    <x-avatar sm label="A" class="bg-green-400" />
    <x-mini-button rounded icon="bell" flat interaction:solid black />
</div>
