<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Edit Holder
        </h2>
    </x-slot>
    <x-splade-modal>
        <x-splade-form :for="$form"/>
    </x-splade-modal>
</x-app-layout>