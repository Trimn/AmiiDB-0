@seoTitle('Speedcodes')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('All Speedcodes') }}
        </h2>
    </x-slot>

    <div id="speedcodes" class="max-w-fit mx-auto p-8">
        <Link slideover href="{{ route('speedcodes.create') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add</Link>
        <x-splade-table :for="$speedcodes" striped />
    </div>
</x-app-layout>