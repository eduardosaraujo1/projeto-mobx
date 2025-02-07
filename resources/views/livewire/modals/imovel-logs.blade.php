<?php

use App\Models\Imovel;
use App\Models\ImovelLog;
use Illuminate\Support\Carbon;
use Livewire\Volt\Component;

new class extends Component
{
    public Imovel $imovel;

    public function with()
    {
        return [
            'logs' => $this->formattedImovelLogs(),
        ];
    }

    public function formattedImovelLogs()
    {
        /**
         * @var \Illuminate\Database\Eloquent\Collection<\App\Models\ImovelLog>
         */
        $logs = $this->imovel->logs;

        return $logs->map(function (ImovelLog $log) {
            $created_at = Carbon::create($log->created_at)
                ->setTimezone('America/Sao_Paulo')
                ->format('d-m-Y H:i:s');

            return [
                'title' => ucfirst($log?->title ?? ''),
                'description' => $log?->description ?? '',
                'user_id' => $log?->user?->id ?? 0,
                'user_name' => substr($log?->user?->name ?? '', 0, 20),
                'created_at' => $created_at ?? '',
            ];
        });
    }
}; ?>


<div>
    <x-modal name="view-logs" focusable>
        <div class="p-6">
            <h1 class="mb-2 text-2xl font-medium">Histórico de Alterações</h1>
            <div class="bg-white border border-gray-200 max-h-[40rem] space-y-2 overflow-y-auto p-2">
                @forelse ($logs as $log)
                    <x-card wire:key="{{$log['id'] ?? uuid_create()}}">
                        <div class="flex">
                            <div class="w-12 h-12 mr-4">
                                <x-icon name="document-text" class="block w-full h-full" />
                            </div>
                            <div class="grid grid-cols-[auto_1fr] gap-x-2 gap-y-1">
                                <span class="text-lg font-bold">Título:</span>
                                <span class="flex items-center">{{ $log["title"] }}</span>
                                <span class="text-lg font-bold">Descrição:</span>
                                <span class="flex items-center">{!! nl2br(e($log["description"])) !!}</span>
                                <span class="text-lg font-bold">Usuário:</span>
                                @can("viewAny", App\Models\User::class)
                                    <a href="{{ route("user.info", ["user" => $log["user_id"]]) }}" class="flex items-center gap-1 text-blue-900">
                                        <x-icon name="information-circle" class="w-4.5 h-4.5" />
                                        {{ $log["user_name"] }}
                                    </a>
                                @else
                                    <span class="flex items-center gap-1">
                                        <x-icon name="information-circle" class="w-4.5 h-4.5" />
                                        {{ $log["user_name"] }}
                                    </span>
                                @endcan
                                <span class="text-lg font-bold">Data:</span>
                                <span class="flex items-center">{{ $log["created_at"] }}</span>
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
    </x-modal>
</div>
