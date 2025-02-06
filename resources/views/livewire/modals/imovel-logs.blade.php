<?php

use App\Models\Imovel;
use Livewire\Volt\Component;

new class extends Component
{
    public Imovel $imovel;

    public function with()
    {
        return [
            'logs' => $this->getImovelLogs(),
        ];
    }

    public function getImovelLogs()
    {
        return $this->imovel->logs;
    }
}; ?>


<div>
    <div class="bg-white border border-gray-200 max-h-[40rem] space-y-2 overflow-y-auto p-2">
        @forelse ($logs as $log)
            <x-card wire:key="{{$log['id'] ?? uuid_create()}}">
                <div class="flex">
                    <div class="w-12 h-12 mr-4">
                        <x-icon name="document-text" class="block w-full h-full" />
                    </div>
                    <div class="grid grid-cols-[auto_1fr] gap-x-2 gap-y-1">
                        <span class="text-lg font-bold">Título:</span>
                        <span class="flex items-center">{{ ucfirst($log["title"] ?? "") }}</span>
                        <span class="text-lg font-bold">Descrição:</span>
                        <span class="flex items-center">{{ $log["description"] ?? "" }}</span>
                        <span class="text-lg font-bold">Usuário:</span>
                        <a href="{{ route("user.info", ["user" => $log->user?->id]) }}" class="flex items-center gap-1 text-blue-900">
                            <x-icon name="information-circle" class="w-4.5 h-4.5" />
                            {{ $log->user?->name }}
                        </a>
                        <span class="text-lg font-bold">Data:</span>
                        <span class="flex items-center">{{ \Illuminate\Support\Carbon::create($log["created_at"]) ?->subHours(3) ?->format("d-m-Y H:m:s") ?? "" }}</span>
                    </div>
                </div>
            </x-card>
        @empty
            <x-alert title="Nenhum registro encontrado" />
        @endforelse
    </div>
    <div class="flex justify-end w-full mt-2">
        <x-primary-button x-on:click="$dispatch('close')">Fechar</x-primary-button>
    </div>
</div>
