<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('CS Chair SAL') }}
        </h2>
    </x-slot>

    <div class="max-w-fit mx-auto p-8">
        <Link slideover href="{{ route('ccai.create') }}" class="float-right p-2 bg-green-900 text-gray-200 rounded-md">Add Holder</Link>
        <x-splade-table :for="$data" striped>
            {{-- @if(Auth::user()->can('delete')) --}}
            <x-splade-cell delete>
                <Link class="p-2 rounded bg-red-500 text-gray-200" method="DELETE" href="/holder/delete/{{ $item->id }}">Delete</Link>
            </x-splade-cell>
            {{-- @endif --}}
        </x-splade-table>
    </div>
</x-app-layout>