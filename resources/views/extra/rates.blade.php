@seoTitle('Rates')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Rates') }}
        </h2>
    </x-slot>

    <div id="rates" class="max-w-fit mx-auto p-8">
        <Link slideover href="{{ route('reference.create', 'rates') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
        <x-splade-table :for="$rates" striped />
    </div>
</x-app-layout>