<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Perfil') }}
        </h2>
    </x-slot>
    <div>
        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            @livewire('profile.update-password-form')
            <x-jet-section-border />
        @endif
        @livewire('profile.logout-other-browser-sessions-form')
    </div>
</x-app-layout>
