@seoTitle('Payments')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Payments') }}
        </h2>
    </x-slot>
    <div class="max-w-fit mx-auto p-8">
        <Link slideover href="{{ route('payments.createGeneric') }}" class="float-right p-2 bg-green-500 text-gray-200 rounded-md">Add Payment</Link>
        <x-splade-table :for="$table" striped />
    </div>
</x-app-layout>