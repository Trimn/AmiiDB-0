<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Contact Preferences') }}
        </h2>
    </x-slot>

    <div class="max-w-fit mx-auto p-8">
        <Link slideover href="{{ route('contact.create') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
        <x-splade-table :for="$table" pagination-scroll="preserve" striped />
    </div>
</x-app-layout>