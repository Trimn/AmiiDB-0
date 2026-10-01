<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-gray-700 leading-tight">
            Add Item
        </h2>
    </x-slot>

    <x-splade-modal stay>
        <div class="max-w-7xl mx-auto p-8">
            <x-splade-form :for='$form' />
            {{-- <x-splade-form :action="route('reference.store', $route)" class="space-y-4">
                @foreach($fields as $field)
                    @switch($field['type'])
                        @case('select')
                            <x-splade-select name="{{ $field['name'] }}" :options="$field['options']" label="{{ $field['label'] }}" />
                            @break
                        @case('input')
                            <x-splade-input name="{{ $field['name'] }}" label="{{ $field['label'] }}" />
                            @break
                        @case('textarea')
                            <x-splade-textarea name="{{ $field['name'] }}" label="{{ $field['label'] }}" autosize />
                            @break
                        @case('checkbox')
                            <x-splade-checkbox name="{{ $field['name'] }}" label="{{ $field['label'] }}" />
                            @break
                        @case('date')
                            <x-splade-input date name="{{ $field['name'] }}" label="{{ $field['label'] }}" />
                            @break
                    @endswitch
                @endforeach
                <x-splade-submit label="Add" />
            </x-splade-form> --}}
        </div>
    </x-splade-modal>
</x-app-layout>