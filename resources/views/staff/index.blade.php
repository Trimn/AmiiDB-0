<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Staff') }}
        </h2>
    </x-slot>

    <div class="max-w-fit mx-auto p-8">
        <Link slideover href="{{ route('staff.create') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add Staff</Link>
        <x-splade-table :for="$staff" striped />
    </div>
</x-app-layout>