@seoTitle('Budgets')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Budgets
        </h2>
    </x-slot>

    <div class="max-w-fit mx-auto p-8">
        @if(Auth::user()->can('edit'))
            <Link slideover href="{{ route('budgets.create') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">New Budget</Link>
        @endif
        <x-splade-table :for="$table" striped />
    </div>
</x-app-layout>


