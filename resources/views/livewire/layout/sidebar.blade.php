<?php
use App\Facades\SelectedImobiliaria;
use App\Models\Imobiliaria;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * @var Collection<Imobiliaria>
     */
    public Collection $user_imobiliarias;

    public ?int $index_imobiliaria;

    /**
     * @var array<array{label: string, active: bool, href: string}>
     */
    public $navbar;

    public function mount()
    {
        $this->user_imobiliarias = auth()->user()->all_imobiliarias;
        $this->index_imobiliaria = SelectedImobiliaria::getIndex();

        // define the array elements
        $this->navbar = $this->defineNavbar();
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
            SelectedImobiliaria::set($value);
            $this->redirect(route('home'), navigate: true);
        }
    }

    public function getAccessLevelName()
    {
        if (auth()->user()->is_admin) {
            return 'Administrador';
        } else {
            return SelectedImobiliaria::accessLevel(auth()->user())->getName() ?? 'Visitante';
        }
    }

    public function defineNavbar()
    {
        $is_admin = auth()->user()->is_admin ?? false;

        $home = [
            'label' => $is_admin ? 'Painel Administrativo' : 'Minha Imobiliária',
            'active' => $this->routeMatches('(admin|imobiliaria|user)'),
            'href' => route('home'),
        ];

        $dashboard = [
            'label' => 'Dashboard',
            'active' => $this->routeMatches('dashboard'),
            'href' => route('dashboard'),
        ];

        $imoveis = [
            'label' => 'Imóveis',
            'active' => $this->routeMatches('imove'),
            'href' => route('imovel.index'),
        ];

        $clients = [
            'label' => 'Clientes',
            'active' => $this->routeMatches('client'),
            'href' => route('client.index'),
        ];

        $navbar = [$home, $dashboard, $imoveis, $clients];

        return $navbar;
    }

    public function routeMatches(string $pattern): bool
    {
        $routeName = Route::currentRouteName();

        return preg_match("/$pattern/", $routeName);
    }
}; ?>


<!-- Navbar Lateral (Responsiva) -->
<nav class="flex flex-col w-64 h-screen p-6 space-y-6 text-gray-800 bg-white border-r border-gray-200 shadow-lg">
    <a class="flex items-center overflow-hidden" href="/">
        <img src="{{ asset("images/mobx.svg") }}" alt="Logo Mobx" class="w-12 h-12 mr-4 bg-black rounded shadow-md" />
        <div class="flex-1 overflow-hidden whitespace-nowrap">
            <h2 class="overflow-hidden text-2xl font-bold">Mobx</h2>
            <h3>
                {{ $level }}
            </h3>
        </div>
    </a>
    <hr />
    <x-native-select label="Imobiliaria Selecionada" wire:model.change="index_imobiliaria" name="imobiliaria_select">
        @foreach ($user_imobiliarias as $imobiliaria)
            <option value="{{ $loop->index }}" @class(["selected" => $loop->index === $index_imobiliaria])>
                {{ Str::limit($imobiliaria->name, 20) }}
            </option>
        @endforeach
    </x-native-select>

    <hr />
    <!-- Links de navegação -->
    <ul>
        @foreach ($navbar as $nav_item)
            <li>
                <x-nav-link :href="$nav_item['href'] ?? '/'" :active="$nav_item['active'] ?? false" wire:navigate>
                    {{ $nav_item["label"] ?? "undefined" }}
                </x-nav-link>
            </li>
        @endforeach
    </ul>
</nav>
