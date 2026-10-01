<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Awards') }}
        </h2>
    </x-slot>

    <div class="max-w-fit mx-auto p-8">
        <x-splade-table :for="$table" striped />
    </div>
</x-app-layout>