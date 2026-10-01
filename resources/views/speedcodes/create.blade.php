<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Add Speedcode
        </h2>
    </x-slot>

    <x-splade-modal>
        <div class="max-w-7xl mx-auto p-8">
            {{-- <x-splade-form :action="route('speedcodes.store')" class="space-y-4">
                <x-splade-input name="code" label="Code" />
                <x-splade-textarea name="description" label="Description" autosize />
                <x-splade-input name="project" label="Project" />
                <x-splade-input name="combo_code" label="Combo Code" />
                <x-splade-select name="fellow" :options="$fellows" label="Fellow" />
                <x-splade-textarea name="notes" label="Notes" />
                <x-splade-submit label="Add" />
            </x-splade-form> --}}
            <x-splade-form :for="$form" />
        </div>
    </x-splade-modal>
</x-app-layout>