<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <x-slot name="slot" id="app" >
        <div id="app">
            <role-index
                :role="{{json_encode($role)}}"
                :permission="{{json_encode($permission)}}"
                :menu="{{json_encode($menu)}}"
                :url="'{{url('/administracion/roles')}}'"
            ></role-index>
        </div>
    </x-slot>
</x-app-layout>

