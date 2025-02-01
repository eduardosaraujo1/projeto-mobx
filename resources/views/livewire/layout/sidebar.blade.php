<?php
use Livewire\Volt\Component;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Imobiliaria;
use App\Enums\AccessLevel;

function getAccessLevel()
{
    $imobiliaria = current_imobiliaria();
    return AccessLevel::nameFrom($imobiliaria->access->level);
}

new class extends Component {
    /**
     * @var Collection<Imobiliaria>
     */
    public $user_imobiliarias;
    public $index_imobiliaria;

    public function mount()
    {
        $this->user_imobiliarias = auth()->user()->imobiliarias;
        $this->index_imobiliaria = Session::get('index_imobiliaria', 0);
    }

    public function updated($name, $value)
    {
        if ($name === 'index_imobiliaria') {
            Session::put('index_imobiliaria', $value);
            $this->js('window.location.reload()');
        }
    }
}; ?>

<!-- Navbar Lateral (Responsiva) -->
<nav
    class="sticky top-0 flex flex-col w-64 h-screen p-6 space-y-6 text-gray-800 bg-white border-r border-gray-200 shadow-lg">
    <a class="flex items-center overflow-hidden" href="/">
        <img src="{{ asset('images/mobx.svg') }}" alt="Logo Mobx" class="w-12 h-12 mr-4 bg-black rounded shadow-md">
        <div class="flex-1 overflow-hidden whitespace-nowrap">
            <h2 class="overflow-hidden text-2xl font-bold">Mobx</h2>
            <h3>
                {{ getAccessLevel() }}
            </h3>
        </div>
    </a>
    <hr>
    <x-native-select label="Imobiliaria Selecionada" wire:model.change='index_imobiliaria' name="imobiliaria_select">
        @foreach ($user_imobiliarias as $imobiliaria)
            <option value="{{ $loop->index }}" @class([
                'selected' => $loop->index === $index_imobiliaria,
            ])>{{ Str::limit($imobiliaria->name, 20) }}
            </option>
        @endforeach
    </x-native-select>

    <hr>
    <!-- Links de navegação -->
    <ul>
        <li>
            <x-nav-link href="{{ route('imobiliaria.index') }}" :active="request()->routeIs('imobiliaria.index')" wire:navigate>
                Minha Imobiliaria
            </x-nav-link>
        </li>
        <li>
            <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" wire:navigate>
                Dashboard
            </x-nav-link>
        </li>
        <li>
            <x-nav-link href="{{ route('imovel.index') }}" :active="request()->routeIs('imovel.index')" wire:navigate>
                Imóveis
            </x-nav-link>
        </li>
        <li>
            <x-nav-link href="{{ route('client.index') }}" :active="request()->routeIs('client.index')" wire:navigate>
                Clientes
            </x-nav-link>
        </li>
    </ul>
    <div class="flex flex-col justify-end flex-1">
        <h1 class="overflow-hidden font-bold overflow-ellipsis">
            {{ getAccessLevel() }}
        </h1>
    </div>
</nav>
