<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            {{ __('Student Appointments') }}
        </h2>
    </x-slot>

    <div class="max-w-fit mx-auto p-8">
        <x-splade-table :for="$appts" striped>
            <x-splade-cell student.notes>
                <div class="max-w-80 max-h-48 overflow-scroll">
                    {!! nl2br(e($item->student->notes)) !!}
                </div>
            </x-splade-cell>
        </x-splade-table>
    </div>
</x-app-layout>