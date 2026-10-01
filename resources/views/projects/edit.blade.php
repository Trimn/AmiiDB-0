<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Edit Project
        </h2>
    </x-slot>
    <x-splade-modal>
        <x-splade-form :for="$form" @success="$splade.emit('projects-updated')" />
    </x-splade-modal>
</x-app-layout>