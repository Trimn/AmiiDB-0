<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Fellows') }}
        </h2>
    </x-slot>

    <div class="max-w-fit mx-auto p-8">
        <Link slideover href="{{ route('fellows.create') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add Fellow</Link>
        <x-splade-table :for="$fellows" striped />
    </div>
</x-app-layout>