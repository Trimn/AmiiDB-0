<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Affiliate Staff and Students') }}
        </h2>
    </x-slot>

    <div class="max-w-fit mx-auto p-8">
        <Link slideover href="{{ route('affiliate_staff.create') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add Student or Staff</Link>
        <x-splade-table :for="$table" striped />
    </div>
</x-app-layout>