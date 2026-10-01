<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('People') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto p-8">
        <x-splade-table :for="$people" striped />
    </div>
</x-app-layout>