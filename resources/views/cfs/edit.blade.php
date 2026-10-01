<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Edit CFS
        </h2>
    </x-slot>
    <x-splade-modal stay>
        <x-splade-form :for="$form" stay />
    </x-splade-modal>
</x-app-layout>