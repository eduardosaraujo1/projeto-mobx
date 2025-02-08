<x-app-layout>
    <h2 class="my-4 text-4xl font-semibold leading-tight">Configurações</h2>
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
