<x-app-layout>
    <x-slot name="heading">
        Configurações
    </x-slot>
    <div class="p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            <livewire:settings.update-profile-information-form />
        </div>
    </div>

    <div class="p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            <livewire:settings.update-password-form />
        </div>
    </div>

    <div class="p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            <livewire:settings.delete-user-form />
        </div>
    </div>
</x-app-layout>
