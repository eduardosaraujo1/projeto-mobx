<?php
use Livewire\Volt\Component;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Imobiliaria;
use App\Services\ImobiliariaService;

function routeMatches(string $pattern): bool
{
    $routeName = Route::currentRouteName();
    return str_contains($routeName, $pattern);
}

new class extends Component {
    /**
     * @var Collection<Imobiliaria>
     */
    public Collection $user_imobiliarias;
    public ?int $index_imobiliaria;

    public function getAccessLevelName()
    {
        if (auth()->user()->is_admin) {
            return 'Administrador';
        } else {
            $level = ImobiliariaService::current_access_level()->name ?? 'Visitante';
            return Str::title($level);
        }
    }
    public function mount()
    {
        // get or define current imobiliaria
        $this->user_imobiliarias = auth()->user()->imobiliarias;
        $this->index_imobiliaria = Session::get('index_imobiliaria', 0);
    }

    public function with()
    {
        return [
            'level' => $this->getAccessLevelName(),
        ];
    }

    // on component update
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
                {{ $level }}
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
            <x-nav-link href="{{ route('imobiliaria.index') }}" :active="routeMatches('imobiliaria')" wire:navigate>
                Minha Imobiliaria
            </x-nav-link>
        </li>
        <li>
            <x-nav-link href="{{ route('dashboard') }}" :active="routeMatches('dashboard')" wire:navigate>
                Dashboard
            </x-nav-link>
        </li>
        <li>
            <x-nav-link href="{{ route('imovel.index') }}" :active="routeMatches('imove')" wire:navigate>
                Imóveis
            </x-nav-link>
        </li>
        <li>
            <x-nav-link href="{{ route('client.index') }}" :active="routeMatches('client')" wire:navigate>
                Clientes
            </x-nav-link>
        </li>
    </ul>
</nav>
