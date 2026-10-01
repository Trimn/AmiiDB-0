<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('My Students and Staff') }}
        </h2>
    </x-slot>

    @include('fellow.partials.view-as-banner')

    <div class="max-w-fit mx-auto p-8">
        <h2 class="p-3 font-bold text-xl">Students</h2>
        <x-splade-table :for="$fellowStudents" striped />
    </div>
    <div class="max-w-fit mx-auto p-8">
        <h2 class="p-3 font-bold text-xl">Staff</h2>
        <x-splade-table :for="$fellowStaff" striped />
    </div>
</x-app-layout>