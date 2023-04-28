<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <x-slot name="slot" id="app" >
        <div id="app">
            <user-index
                :user="{{json_encode($user)}}"
                :role="{{json_encode($role)}}"
                :url="'{{url('/administracion/usuarios')}}'"
            ></user-index>
        </div>
    </x-slot>
</x-app-layout>

