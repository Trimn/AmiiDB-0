<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Add Contact Preferences
        </h2>
    </x-slot>

    <x-splade-modal>
        <div class="max-w-7xl mx-auto p-8">
            <x-splade-form :for="$form" stay />
        </div>
    </x-splade-modal>
</x-app-layout>